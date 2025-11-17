; Čo sa stane?
; Ako by si sa pri tom cítil?
; Čo s tým budeme robiť?

HotKeySet("^+!e", "_ExitScript") ; Ctrl-Shift-Alt-e

Sleep(3000)

While 1
    $CurrentPosition = MouseGetPos()
    $NewXCoordinate = $CurrentPosition[0] + 40
    DllCall("user32.dll", "bool", "SetCursorPos", "int", $NewXCoordinate, "int", $CurrentPosition[1])
    Sleep(1000)
WEnd

Func _ExitScript()
    Exit
EndFunc

; Zadanie pre teba: Uprav skript tak, aby sa kurzor hýbal aj inými smermi.
; Zadanie pre AI: Ako pred ukončením skriptu používateľovi oznámiť, že sa skript ukončuje?