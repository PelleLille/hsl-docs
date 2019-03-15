Connection
++++++++++

================= ======= ========================== ===========
Array item        Type    Example                    Description
================= ======= ========================== ===========
remoteip          string  "192.168.1.11"             IP address of the connected client
remoteport        number  41666                      TCP port of connected client
localip           string  "10.0.0.1"                 IP address of the server
localport         number  25                         TCP port of the server
serverid          string  "inbound"                  ID of the server
helohost          string  "mail.example.com"         HELO hostname of sender (not always available)
tlsstarted        boolean false                      Whether or not the SMTP session is using TLS
saslauthed        boolean true                       Whether or not the SMTP session is authenticated (SASL)
saslusername      string  "mailuser"                 SASL username (not always available)
================= ======= ========================== ===========