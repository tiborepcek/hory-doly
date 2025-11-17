; Čo sa stane?
; Ako by si sa pri tom cítil?
; Čo s tým budeme robiť?

Run("Notepad")
WinWait("[CLASS:Notepad]")
WinSetOnTop("[CLASS:Notepad]", "", 1)
WinSetState("[CLASS:Notepad]", "", @SW_MAXIMIZE)
WinActivate("[CLASS:Notepad]")
Send("BU, BU, BU!", 1)

; Zadanie pre teba: Ulož si aktuálny stav cez Git.
; Zadanie pre AI: Prečo je dobré používať Git?