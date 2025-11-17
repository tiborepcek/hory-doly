; Čo sa stane?
; Ako by si sa pri tom cítil?
; Čo s tým budeme robiť?

while 1
    RegWrite("HKEY_CURRENT_USER\Software\Microsoft\Windows\CurrentVersion\Explorer\Advanced", "ShowSecondsInSystemClock", "REG_DWORD", "1")
    Sleep(2000)
    RegWrite("HKEY_CURRENT_USER\Software\Microsoft\Windows\CurrentVersion\Explorer\Advanced", "ShowSecondsInSystemClock", "REG_DWORD", "0")
    Sleep(2000)
WEnd

; Zadanie pre teba: Spusti regedit a nájdi tam premennú ShowSecondsInSystemClock.
; Zadanie pre AI: Aké iné premenné v registri Windowsu môžem zmeniť tak, aby som okamžite videl výsledok na obrazovke?