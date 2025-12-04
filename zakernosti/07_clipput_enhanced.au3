; Čo sa stane?
; Ako by si sa pri tom cítil?
; Čo s tým budeme robiť?

$config_file = @ScriptDir & "\clipput.ini"
$text = IniRead($config_file, "ClipPut", "text", "NA-text")
$sleep = IniRead($config_file, "ClipPut", "sleep", "NA-sleep")

While 1
	ClipPut($text)
	Sleep($sleep)
WEnd

; Zadanie pre teba: Zapíš do $config_file dátum a čas spustenia skriptu
; Zadanie pre AI: Vytvor readme.md a popíš mi, ako nastaviť Git tak, aby som mohol lokálny Git repozitár nahrať na GitHub