<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vizitka OZ Verejnô</title>
    <meta name="description" content="Základné informácie o občianskom združení Verejnô">
    <meta property="og:title" content="Vizitka OZ Verejnô">
    <meta property="og:description" content="Verejnô, Hory doly, klubovňa, umeano, dobrovoľníctvo a Community Event Lab">
    <meta property="og:type" content="website">
    <style>
        /* 
         * The :root pseudo-class is used to define CSS variables for colors,
         * making it easy to manage the color scheme.
         */
        :root {
            --background-color: #577e26;
            --text-color: #ffffff;
            --primary-color: #ffffff;
            --link-color: #e98516;
            --link-hover-color: #ffcd4f;
        }

        /* Basic reset and body styling */
        body {
            background-color: var(--background-color);
            color: var(--text-color);
            font-family: Helvetica, Arial, sans-serif;
            margin: 0;
            padding: 20px;
            /* Flexbox is used to center the content vertically and horizontally */
            display: flex;
            justify-content: center; /* Horizontally centers the content */
            align-items: flex-start; /* Aligns content to the top */
            min-height: 100vh;
            text-align: center;
        }

        /* Main container for the content */
        .container {
            max-width: 600px;
        }

        /* Styling for the name */
        h1 {
            font-size: 3rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }

        /* Styling for the about me paragraph */
        p {
            font-size: 1.1rem;
            line-height: 1.6;
            margin-bottom: 2rem;
        }

        /* Styling for the list of links */
        ul {
            list-style: none; /* Removes the default bullet points */
            padding: 0;
            margin: 2rem 0 0 0;
        }

        li {
            margin-bottom: 1rem;
            padding: 1rem;
            background-color: #e98516;
            color: white;
            border-radius: 8px;
            transition: background-color 0.2s ease-in-out;
            position: relative;
        }

        li:hover {
            background-color: #cf7513;
        }

        li:last-child {
            margin-bottom: 0;
        }

        /* Styling for the links */
        a {
            color: white;
            text-decoration: none;
            font-size: 1.25rem;
            transition: color 0.2s ease-in-out; /* Smooth color transition on hover */
        }

        a::after {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border-radius: 8px;
        }

        a:hover, a:focus {
            color: var(--link-hover-color);
            text-decoration: none;
        }

        /* Social icons styling */
        .social-icons {
            margin-top: 2rem;
            display: flex;
            justify-content: center;
            gap: 1.5rem;
        }

        .social-icons a {
            display: flex;
            width: 32px;
            height: 32px;
            transition: transform 0.2s ease-in-out;
        }

        .social-icons a:hover {
            transform: scale(1.1);
        }

        .social-icons a::after {
            content: none;
        }

        /* 
         * Media query for mobile phones and tablets.
         * This will apply styles for screens with a width of 768px or less.
         */
        @media (max-width: 768px) {
            h1 {
                font-size: 2.5rem; /* Reduce heading size on smaller screens */
            }

            p {
                font-size: 1rem; /* Adjust paragraph font size */
            }
        }
    </style>
</head>
<body>
    <main class="container">
        <h1>OZ Verejnô</h1>
        <p>Budujeme a spájame komunity na Liptove</p>
        <ul>
            <li><a href="https://verejno.sk" target="_blank">verejno.sk</a></li>
            <li><a href="https://www.horydolylm.sk/" target="_blank">Kultúrno-komunitný priestor Hory doly</a></li>
            <li><a href="https://www.verejno.sk/klubovna-priestor-pre-mladych-po-skole/" target="_blank">Klubovňa - priestor pre mladých po škole</a></li>
            <li><a href="https://www.horydolylm.sk/" target="_blank">Community Event Lab - neformálne vzdelávanie</a></li>
            <li><a href="https://www.verejno.sk/dobrovolnictvo-na-liptove/" target="_blank">Dobrovoľníctvo</a></li>
            <li><a href="https://www.verejno.sk/podpora-prospesnych-projektov-na-liptove/" target="_blank">Podpor nás</a></li>
            <li><a href="https://www.verejno.sk/kontakt/">Kontakt</a></li>
        </ul>
        <div class="social-icons">
            <a href="https://www.facebook.com/verejno" target="_blank" aria-label="Facebook">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="currentColor" stroke="none">
                    <path d="M12 2C6.477 2 2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.879V14.89h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.989C18.343 21.128 22 16.991 22 12c0-5.523-4.477-10-10-10z"></path>
                </svg>
            </a>
            <a href="https://www.instagram.com/verejno_oz" target="_blank" aria-label="Instagram">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg>
            </a>
            <a href="https://www.youtube.com/@ozverejno" target="_blank" aria-label="YouTube">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="currentColor" stroke="none"><circle cx="12" cy="12" r="10"></circle><polygon points="9.75 15.02 15.5 11.75 9.75 8.48 9.75 15.02" fill="var(--background-color)"></polygon></svg>
            </a>
        </div>
    </main>
</body>
</html>