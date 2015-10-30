.. module:: postdelivery

Post-delivery
=============

The post-delivery script is executed after a delivery attempt.

Pre-defined variables
---------------------

These are the read-only pre-defined variables available each time after a delivery attempt is made. 

================= ======= ========================== ===========
Variable          Type    Example                    Description
================= ======= ========================== ===========
$receivedtime     number  1445937340                 The unix time (in UTC) when the message was received
$serverip         string  "172.16.1.25"              IP which we tried to connect to (empty on DNS problems)
$serverport       number  25                         Port which we tried to connect to
$senderip         string  "192.168.1.11"             IP address of the sender
$saslusername     string  "mailuser"                 SASL username
$sender           string  "test\@example.org"        E-mail address of sender
$senderdomain     string  "example.org"              Domain part of sender's address
$recipient        string  "test\@example.com"        E-mail address of recipient
$recipientdomain  string  "example.com"              Domain part of recipient's address
$retry            number  3                          The current retry count
$retries          number  30                         The maximum number of retries for that message
$errormsg         string  "5.7.1... we do not relay" The error message from the server 
$errorcode        number  550                        The error code from the server (A value 0 of indicates network problems)
$errorndr         string  "5.7.1"                    The NDR code from the server (if available)
$transfertime     number  0.512                      The transfer time for this delivery attempt (seconds)
$messageid        string  "18c190a3-93f-47d7-bd..."  ID of the message
$actionid         number  1                          Same as $actionid in DATA context
$queueid          number  12345                      Queue ID of the message
$transportid      string  "mailtransport\:1"         ID of the transport profile that was used
================= ======= ========================== ===========

Functions
---------

.. function:: Delete()

  Delete the message from the queue, without generating a DSN (bounce) to the sender.

  :return: doesn't return, script is terminated

  .. warning::

     If the message was delivered (``$errorcode == 250``) this function will raise a runtime error.

.. function:: GenerateDSN()

  Delete the message from the queue, and generating a DSN (bounce) to the sender.

  :return: doesn't return, script is terminated

  .. warning::

     If the message was delivered (``$errorcode == 250``) this function will raise a runtime error.

.. function:: Retry()

  Retry the message again later. This is the default action for non-permanent (5XX) ``$errorcode``'s. If the maximum retry count is exceeded; the message is either bounced or deleted depending on the transport's settings.

  :return: doesn't return, script is terminated

  .. warning::

     If the message was delivered (``$errorcode == 250``) this function will raise a runtime error.

.. function:: Deliver(recipient, transportid)

  Deliver the message to a new recipient and/or transport. The retry count is reset and the message is queued for immediate delivery. 

  :param string recipient: an e-mail address
  :param string transportid: the transportid to be used
  :return: doesn't return, script is terminated

  .. warning::

     If the message was delivered (``$errorcode == 250``) this function will raise a runtime error.

.. function:: SetMetaData(metadata)

  This function sets the metadata for the current message. The metadata must be an array with both string keys and values.

  :param array metadata: metadata to set
  :rtype: none

  .. note::

    To work-around the data type limitation of the metadata; data can be encoded using :func:`json_encode`.

.. function:: GetMetaData()

  Get the metadata set by :func:`SetMetaData`. If no data was set, an empty array is returned.

  :return: the data set by :func:`SetMetaData`
  :rtype: array

On script error
---------------

On script error the default action is taken.
