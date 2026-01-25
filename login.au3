#include <GUIConstantsEx.au3>
#include <ButtonConstants.au3>
#include <EditConstants.au3>
#include <WindowsConstants.au3>

; Vytvorenie hlavného okna GUI
$hGUI = GUICreate("Prihlásenie", 600, 160)

; Vytvorenie popisov a vstupných polí
GUICtrlCreateLabel("Meno:", 20, 25, 60, 20)
$idInputUser = GUICtrlCreateInput("", 80, 22, 150, 20)

GUICtrlCreateLabel("Heslo:", 20, 55, 60, 20)
$idInputPass = GUICtrlCreateInput("", 80, 52, 150, 20, $ES_PASSWORD)

; Tlačidlo na odoslanie
$idBtnLogin = GUICtrlCreateButton("Prihlásiť", 80, 90, 80, 30, $BS_DEFPUSHBUTTON)

; Edit pole pre logovanie správ (zobrazí sa vedľa polí)
$idLog = GUICtrlCreateEdit("", 260, 20, 320, 120, BitOR($ES_AUTOVSCROLL, $WS_VSCROLL, $ES_READONLY))

; Zobrazenie okna
GUISetState(@SW_SHOW)

; Hlavná slučka programu
While 1
    Switch GUIGetMsg()
        Case $GUI_EVENT_CLOSE
            ExitLoop
        Case $idBtnLogin
            CheckLogin()
    EndSwitch
WEnd

Func CheckLogin()
    Local $sUser = GUICtrlRead($idInputUser)
    Local $sPass = GUICtrlRead($idInputPass)
    Local $sTime = StringFormat("%02d.%02d.%04d %02d:%02d:%02d", @MDAY, @MON, @YEAR, @HOUR, @MIN, @SEC)

    ; Kontrola mena a hesla (tu si môžete nastaviť vlastné údaje)
    If $sUser == "admin" And $sPass == "heslo" Then
        GUICtrlSetData($idLog, $sTime & " - Úspešné prihlásenie" & @CRLF, 1)
    Else
        GUICtrlSetData($idLog, $sTime & " - Neúspešné prihlásenie" & @CRLF, 1)
    EndIf
EndFunc
