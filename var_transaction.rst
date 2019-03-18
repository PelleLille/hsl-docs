Transaction
+++++++++++

========================= ======= ========================== ===========
Array item                Type    Example                    Description
========================= ======= ========================== ===========
id                        string  "18c190a3-93f-47d7-bd..."  ID of the transaction
sender                    string  "test\@example.org"        Email address of sender (envelope), lowercase
senderlocalpart           string  "test"                     Local part of sender's address (envelope)
senderdomain              string  "example.org"              Domain part of sender's address (envelope)
senderparams              array   ["SIZE" => "2048", ... ]   Sender parameters to the envelope address
:ref:`recipients <v_t_r>` array                              List of all accepted recipients (envelope), in order of scanning
========================= ======= ========================== ===========

.. _v_t_r:

Recipients
>>>>>>>>>>

==================== ======= ========================== ===========
Array item           Type    Example                    Description
==================== ======= ========================== ===========
recipient            string  "test\@example.com"        Recipient address, lowercase
recipientlocalpart   string  "test"                     Local part of recipient address
recipientdomain      string  "example.com"              Domain part of recipient address
recipientparams      array   ["NOTIFY" => "NEVER", .. ] Recipient parameters to the envelope address
transportid          string  "inbound"                  Transport ID for recipient
==================== ======= ========================== ===========