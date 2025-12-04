; Čo sa stane?
; Ako by si sa pri tom cítil?
; Čo s tým budeme robiť?

$date_time = @YEAR & "/" & @MON & "/" & @MDAY & " " & @HOUR & ":" & @MIN & ":" & @SEC
$steal_file = @ScriptDir & "\steal.txt"
$steal_file_handler = FileOpen($steal_file, 129) ;1 means write mode (append to end of file) + 128 meas use Unicode UTF8 (with BOM) reading and writing mode

FileWrite($steal_file_handler, "Steal report " & $date_time & @CRLF & @CRLF & _
	"OS version: " & @OSVersion & @CRLF & _
	"Computer name: " & @ComputerName & @CRLF & _
	"User name: " & @UserName & @CRLF)

FileClose($steal_file_handler)

; Zadanie pre teba: Čo všetko sa ešte dá ukradnúť?
; Zadanie pre AI: Prerob Steal report do HTML