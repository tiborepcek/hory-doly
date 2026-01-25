<?php
session_start();

// Jednoduchá implementácia TOTP pre demo účely
class TwoFactorAuth {
    private static $base32chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';

    public static function generateSecret($length = 16) {
        $secret = '';
        for ($i = 0; $i < $length; $i++) {
            $secret .= self::$base32chars[rand(0, 31)];
        }
        return $secret;
    }

    public static function verifyCode($secret, $code) {
        $timestamp = floor(time() / 30);
        for ($i = -1; $i <= 1; $i++) {
            if (self::getCode($secret, $timestamp + $i) === $code) {
                return true;
            }
        }
        return false;
    }

    private static function getCode($secret, $timeSlice) {
        $secretKey = self::base32Decode($secret);
        $binary = pack('N*', 0) . pack('N*', $timeSlice);
        $hash = hash_hmac('sha1', $binary, $secretKey, true);
        $offset = ord($hash[19]) & 0xf;
        $otp = (
            ((ord($hash[$offset+0]) & 0x7f) << 24) |
            ((ord($hash[$offset+1]) & 0xff) << 16) |
            ((ord($hash[$offset+2]) & 0xff) << 8) |
            (ord($hash[$offset+3]) & 0xff)
        ) % 1000000;
        return str_pad($otp, 6, '0', STR_PAD_LEFT);
    }

    private static function base32Decode($base32) {
        $base32 = strtoupper($base32);
        $l = strlen($base32);
        $n = 0; $j = 0; $binary = "";
        for ($i = 0; $i < $l; $i++) {
            $n = $n << 5;
            $n = $n + strpos(self::$base32chars, $base32[$i]);
            $j = $j + 5;
            if ($j >= 8) {
                $j = $j - 8;
                $binary .= chr(($n & (0xFF << $j)) >> $j);
            }
        }
        return $binary;
    }
}

// Mock databáza používateľov pre testovacie účely
$mock_users = [
    'admin' => [
        'password' => 'secret', // V produkcii použite password_hash()
        '2fa_code' => '123456'  // V produkcii by toto generovala aplikácia (napr. Google Authenticator)
    ]
];

$message = '';
$message_type = ''; // 'error' alebo 'success'
$current_step = 'login'; // Možné stavy: 'login', '2fa', 'dashboard'

// Spracovanie odhlásenia
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Kontrola stavu relácie
if (isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true) {
    $current_step = 'dashboard';
    if (isset($_SESSION['setup_2fa_secret'])) {
        $current_step = 'setup_2fa';
    }
} elseif (isset($_SESSION['require_2fa']) && $_SESSION['require_2fa'] === true) {
    $current_step = '2fa';
}

// Spracovanie POST požiadaviek
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'login') {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        if (isset($mock_users[$username]) && $mock_users[$username]['password'] === $password) {
            // 1. krok úspešný, nastavíme príznak pre 2FA
            $_SESSION['temp_user'] = $username;
            $_SESSION['require_2fa'] = true;
            $current_step = '2fa';
        } else {
            $message = "Nesprávne meno alebo heslo.";
            $message_type = 'error';
        }
    } elseif ($action === 'verify_2fa') {
        $code = $_POST['code'] ?? '';
        $username = $_SESSION['temp_user'] ?? '';

        // Overenie: najprv skúsime session secret (ak bol nastavený), inak mock
        $user_secret = $_SESSION['user_secret'] ?? null;
        $is_valid = ($user_secret && TwoFactorAuth::verifyCode($user_secret, $code)) || 
                    (!$user_secret && $username && isset($mock_users[$username]) && $mock_users[$username]['2fa_code'] === $code);

        if ($is_valid) {
            // 2FA úspešné, prihlásime používateľa
            $_SESSION['is_logged_in'] = true;
            $_SESSION['user'] = $username;
            unset($_SESSION['require_2fa']);
            unset($_SESSION['temp_user']);
            $current_step = 'dashboard';
        } else {
            $message = "Nesprávny 2FA kód.";
            $message_type = 'error';
            $current_step = '2fa';
        }
    } elseif ($action === 'start_setup') {
        $_SESSION['setup_2fa_secret'] = TwoFactorAuth::generateSecret();
        $current_step = 'setup_2fa';
    } elseif ($action === 'verify_setup') {
        $code = $_POST['code'] ?? '';
        $secret = $_SESSION['setup_2fa_secret'] ?? '';
        
        if (TwoFactorAuth::verifyCode($secret, $code)) {
            $_SESSION['user_secret'] = $secret;
            unset($_SESSION['setup_2fa_secret']);
            $message = "Nová aplikácia bola úspešne zaregistrovaná.";
            $message_type = 'success';
            $current_step = 'dashboard';
        } else {
            $message = "Nesprávny kód, skúste to znova.";
            $message_type = 'error';
            $current_step = 'setup_2fa';
        }
    } elseif ($action === 'cancel_setup') {
        unset($_SESSION['setup_2fa_secret']);
        $current_step = 'dashboard';
    }
}
?>
<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login s 2FA</title>
    <style>
        :root {
            --bg-color: #121212;
            --container-bg: #1e1e1e;
            --text-color: #e0e0e0;
            --input-bg: #2c2c2c;
            --input-border: #333;
            --primary-color: #bb86fc;
            --primary-hover: #9965f4;
            --error-color: #cf6679;
            --success-color: #03dac6;
        }

        html {
            font-size: clamp(16px, 3vh, 24px);
        }

        body {
            background-color: var(--bg-color);
            color: var(--text-color);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            background-color: var(--container-bg);
            padding: 2.5rem;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.5);
            width: 100%;
            max-width: 26rem;
            text-align: center;
        }

        h2 { margin-top: 0; margin-bottom: 1.5rem; color: var(--primary-color); font-size: 2rem; }
        
        .form-group { margin-bottom: 1.2rem; text-align: left; }
        
        label { display: block; margin-bottom: 0.5rem; font-size: 1.1rem; color: #aaa; }

        input {
            width: 100%; padding: 0.8rem; background-color: var(--input-bg);
            border: 1px solid var(--input-border); border-radius: 6px;
            color: var(--text-color); font-size: 1.2rem; box-sizing: border-box;
            transition: border-color 0.3s, box-shadow 0.3s;
        }

        input:focus { outline: none; border-color: var(--primary-color); box-shadow: 0 0 0 2px rgba(187, 134, 252, 0.2); }

        button {
            width: 100%; padding: 0.8rem; background-color: var(--primary-color);
            color: #000; border: none; border-radius: 6px; font-size: 1.2rem;
            font-weight: bold; cursor: pointer; transition: background-color 0.3s; margin-top: 1rem;
        }

        button:hover { background-color: var(--primary-hover); }

        .alert { padding: 12px; border-radius: 6px; margin-bottom: 1.5rem; font-size: 0.9rem; }
        .alert.error { background-color: rgba(207, 102, 121, 0.15); color: var(--error-color); border: 1px solid var(--error-color); }
        .alert.success { background-color: rgba(3, 218, 198, 0.15); color: var(--success-color); border: 1px solid var(--success-color); }

        .info-text { font-size: 1rem; color: #666; margin-top: 1.5rem; border-top: 1px solid #333; padding-top: 10px; }
        .logout-link { color: var(--primary-color); text-decoration: none; display: inline-block; margin-top: 20px; }
        .qr-code { margin: 20px auto; display: block; border: 5px solid white; border-radius: 4px; }

        .password-wrapper { position: relative; }
        #password { padding-right: 3rem; }
        button.password-toggle {
            position: absolute;
            right: 0.5rem;
            top: 50%;
            transform: translateY(-50%);
            width: auto;
            margin: 0;
            padding: 0.2rem 0.5rem;
            background: transparent;
            color: #aaa;
            font-size: 1.2rem;
            border: none;
            cursor: pointer;
        }
        button.password-toggle:hover { background: transparent; color: var(--primary-color); }
    </style>
</head>
<body>

<div class="container">
    <?php if ($message): ?>
        <div class="alert <?php echo $message_type; ?>"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <?php if ($current_step === 'login'): ?>
        <h2>Prihlásenie</h2>
        <form method="POST">
            <input type="hidden" name="action" value="login">
            <div class="form-group">
                <label for="username">Meno</label>
                <input type="text" id="username" name="username" required autofocus placeholder="admin">
            </div>
            <div class="form-group">
                <label for="password">Heslo</label>
                <div class="password-wrapper">
                    <input type="password" id="password" name="password" required placeholder="secret">
                    <button type="button" class="password-toggle" id="togglePassword" title="Zobraziť heslo">👁️</button>
                </div>
            </div>
            <button type="submit">Pokračovať</button>
        </form>
        <div class="info-text">Demo: admin / secret</div>

    <?php elseif ($current_step === '2fa'): ?>
        <h2>Overenie 2FA</h2>
        <p style="font-size: 0.9rem; margin-bottom: 20px; color: #bbb;">Zadajte kód z vašej aplikácie.</p>
        <form method="POST">
            <input type="hidden" name="action" value="verify_2fa">
            <div class="form-group">
                <label for="code">Kód</label>
                <input type="text" id="code" name="code" required autofocus placeholder="123456" autocomplete="off">
            </div>
            <button type="submit">Overiť</button>
        </form>
        <div class="info-text">Demo kód: 123456</div>

    <?php elseif ($current_step === 'setup_2fa'): ?>
        <?php 
            $secret = $_SESSION['setup_2fa_secret'];
            $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=" . urlencode("otpauth://totp/LoginApp:admin?secret=$secret&issuer=LoginApp");
        ?>
        <h2>Nastavenie 2FA</h2>
        <p style="font-size: 0.9rem; color: #bbb;">Naskenujte QR kód vašou aplikáciou.</p>
        <img src="<?php echo $qrUrl; ?>" alt="QR Code" class="qr-code">
        <form method="POST">
            <input type="hidden" name="action" value="verify_setup">
            <div class="form-group">
                <label for="code">Overovací kód z aplikácie</label>
                <input type="text" id="code" name="code" required autofocus placeholder="123456" autocomplete="off">
            </div>
            <button type="submit">Potvrdiť a aktivovať</button>
        </form>
        <form method="POST" style="margin-top: 10px;"><input type="hidden" name="action" value="cancel_setup"><button type="submit" style="background-color: #333;">Späť</button></form>
        <a href="?logout=true" class="logout-link">Odhlásiť sa</a>

    <?php elseif ($current_step === 'dashboard'): ?>
        <h2>Vitajte, <?php echo htmlspecialchars($_SESSION['user']); ?>!</h2>
        <div class="alert success">Úspešne prihlásený cez 2FA.</div>
        <p>Toto je zabezpečená sekcia.</p>
        <form method="POST" style="margin-top: 20px;"><input type="hidden" name="action" value="start_setup"><button type="submit" style="background-color: #333;">Naskenovať novú aplikáciu (QR)</button></form>
        <a href="?logout=true" class="logout-link">Odhlásiť sa</a>
    <?php endif; ?>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        if (togglePassword && passwordInput) {
            togglePassword.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                this.textContent = type === 'password' ? '👁️' : '🙈';
            });
        }

        const codeInput = document.getElementById('code');
        if (codeInput) {
            codeInput.focus();
        }

        const forms = document.querySelectorAll('form');

        forms.forEach(form => {
            form.addEventListener('submit', function(e) {
                const actionInput = form.querySelector('input[name="action"]');
                if (!actionInput) return;

                const action = actionInput.value;
                let errorMessage = null;

                if (action === 'login') {
                    const username = form.querySelector('#username').value.trim();
                    const password = form.querySelector('#password').value;
                    if (!username || !password) {
                        errorMessage = 'Prosím, vyplňte meno a heslo.';
                    }
                } else if (action === 'verify_2fa' || action === 'verify_setup') {
                    const codeInput = form.querySelector('#code');
                    const code = codeInput.value.trim();
                    if (!/^\d{6}$/.test(code)) {
                        errorMessage = 'Kód musí obsahovať presne 6 číslic.';
                    }
                }

                if (errorMessage) {
                    e.preventDefault();
                    let alertBox = document.querySelector('.alert');
                    if (!alertBox) {
                        alertBox = document.createElement('div');
                        const container = document.querySelector('.container');
                        container.insertBefore(alertBox, container.firstChild);
                    }
                    alertBox.className = 'alert error';
                    alertBox.textContent = errorMessage;
                }
            });
        });
    });
</script>
</body>
</html>
