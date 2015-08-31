; <?
; this is the INI file for the cf

[common]
       txtpreviewlen = 150                            ; sets the number of characters for text preview windows
            timezone = "Europe/Samara"                ; default timezone
     sessionsavepath = "data/sessions"                ; folder where to store session system data files
      exportsavepath = "data/temp"                    ; folder where to store temporary files
    enableajaxrecode = 0                              ; set to 1 to solve ajax codepage errors if any, else set to 0
     oldrecordsthres = 0                              ; old records threshold in days. set to 0 to disable old records presence check
        defaulttheme = "modern";

[database]
              dbname = "u0037808_kadastr"             ; Database name
              dbuser = "u0037808_kadastr"             ; Database user
          dbpassword = "l9gvvVZB"                     ; Database password
              dbhost = "194.58.110.104"               ; Database server
            sqllimit = 0                            ; SQL Output Limit. place zero value here to disable this.
     displaydberrors = 0;                             ; set to 1 to enable display of DB errors

; ?>
