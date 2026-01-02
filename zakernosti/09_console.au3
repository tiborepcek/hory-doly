#pragma compile(Console, true)

#cs
Compile this script and run it in command line with argument like in example:

09_console.exe /name Tibor
#ce

; Check if any arguments were provided
If $CmdLine[0] == 0 Then
    ConsoleWriteError(@CRLF & "Error: No arguments provided." & @CRLF & @CRLF)
    Exit 1 ; Exit with an error code
EndIf

; Loop through arguments to find a specific one
For $i = 1 To $CmdLine[0]
    If $CmdLine[$i] = "/name" And $i < $CmdLine[0] Then
        $userName = $CmdLine[$i + 1]
        ConsoleWrite("Hello, " & $userName & "!" & @CRLF)
        Exit 0 ; Success
    EndIf
Next

ConsoleWrite(@CRLF & "Status: Task completed successfully." & @CRLF & @CRLF)

; Zadanie pre teba: Dodaj skriptu zákernosť!