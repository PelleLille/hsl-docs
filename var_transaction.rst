================= ======= ========================== ===========
Variable          Type    Example                    Description
================= ======= ========================== ===========
$transaction      array   [...]                      Contains the transaction ID and envelope data (sender and recipient)
================= ======= ========================== ===========

The ``$transaction`` variable is an array with

================= ======= ========================== ===========
Array item        Type    Example                    Description
================= ======= ========================== ===========
"id"              string  "18c190a3-93f-47d7-bd..."  ID of the transaction
"senderdomain"    string  "example.org"              Domain part of sender's address (envelope)
"sender"          string  "test\@example.org"        E-mail address of sender (envelope)
"senderparams"    array   ["SIZE" => "2048", ... ]   Sender parameters to the envelope address
"recipients"      array   [...]                      List of all accepted recipients (envelope), in order of scanning
================= ======= ========================== ===========

where ``"recipients"`` is an array with

==================== ======= ========================== ===========
Array item           Type    Example                    Description
==================== ======= ========================== ===========
"recipient"          string  "test\@example.com"        Recipient address
"recipientlocalpart" string  "example.com"              Local part of all recipient address
"recipientdomain"    string  "example.com"              Domain part of all recipient address
"recipientparams"    array   ["NOTIFY" => "NEVER", .. ] Recipient parameters to the envelope address
==================== ======= ========================== ===========
