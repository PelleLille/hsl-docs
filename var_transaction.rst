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
"sender"          string  "test\@example.org"        Email address of sender (envelope), lowercase
"senderlocalpart" string  "test"                     Local part of sender's address (envelope)
"senderdomain"    string  "example.org"              Domain part of sender's address (envelope)
"senderparams"    array   ["SIZE" => "2048", ... ]   Sender parameters to the envelope address
"recipients"      array   [...]                      List of all accepted recipients (envelope), in order of scanning
================= ======= ========================== ===========

where ``"recipients"`` is an array with

==================== ======= ========================== ===========
Array item           Type    Example                    Description
==================== ======= ========================== ===========
"recipient"          string  "test\@example.com"        Recipient address, lowercase
"recipientlocalpart" string  "test"                     Local part of recipient address
"recipientdomain"    string  "example.com"              Domain part of recipient address
"recipientparams"    array   ["NOTIFY" => "NEVER", .. ] Recipient parameters to the envelope address
"transportid"        string  "mx"                       Transport ID for recipient
==================== ======= ========================== ===========
