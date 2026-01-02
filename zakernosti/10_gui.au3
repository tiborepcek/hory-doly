#include <GUIConstantsEx.au3>

; Create the GUI window
$hGUI = GUICreate("Greeting App", 300, 150)

; Create controls
GUICtrlCreateLabel("Name:", 10, 15, 40, 20)
$idInput = GUICtrlCreateInput("", 50, 12, 200, 20)
$idButton = GUICtrlCreateButton("Send", 50, 45, 80, 30)
$idLabel = GUICtrlCreateLabel("", 50, 90, 240, 30)

; Show the GUI
GUISetState(@SW_SHOW)

; Main message loop
While 1
    Switch GUIGetMsg()
        Case $GUI_EVENT_CLOSE
            ExitLoop
        Case $idButton
            ; Read the input and update the label
            $sName = GUICtrlRead($idInput)
            GUICtrlSetData($idLabel, "Hello, " & $sName & "!")
    EndSwitch
WEnd

GUIDelete($hGUI)

; Zadanie pre teba: Dodaj skriptu zákernosť!