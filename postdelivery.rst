.. module:: postdelivery

Post-delivery
=============

The post-delivery script is executed after a delivery attempt or when a message is deleted or bounced from the queue. If the message was deleted from the queue (either manually or by retention) the ``$context`` variable will not be defined.

Pre-defined variables
---------------------

These are the read-only pre-defined variables available each time after a delivery attempt is made.

Original message
^^^^^^^^^^^^^^^^

These variables are related to the queued message.

Connection
""""""""""

=================== ======= ========================== ===========
Variable            Type    Example                    Description
=================== ======= ========================== ===========
$senderip           string  "192.168.1.11"             IP address of the sender
$saslusername       string  "mailuser"                 SASL username
=================== ======= ========================== ===========

Transaction
"""""""""""""""""""""

=================== ======= ========================== ===========
Variable            Type    Example                    Description
=================== ======= ========================== ===========
$messageid          string  "18c190a3-93f-47d7-bd..."  ID of the message
$sender             string  "test\@example.org"        Email address of sender (envelope), lowercase
$senderlocalpart    string  "test"                     Local part of sender's address (envelope)
$senderdomain       string  "example.org"              Domain part of sender's address (envelope)
$recipient          string  "test\@example.com"        Email address of recipient (envelope), lowercase
$recipientlocalpart string  "test"                     Local part of recipient's address (envelope)
$recipientdomain    string  "example.com"              Domain part of recipient's address (envelope)
$receivedtime       number  1445937340                 The unix time (in UTC) when the message was received
$actionid           number  1                          Same as $actionid in DATA context
=================== ======= ========================== ===========

Queue
^^^^^

=================== ======= ========================== ===========
Variable            Type    Example                    Description
=================== ======= ========================== ===========
$transportid        string  "mailtransport\:1"         ID of the transport profile that was used
$queueid            number  12345                      Queue ID of the message
$retry              number  3                          The current retry count
$retries            number  30                         The maximum number of retries for that message
=================== ======= ========================== ===========

Arguments
^^^^^^^^^

=================== ======= ========================== ===========
Variable            Type    Example                    Description
=================== ======= ========================== ===========
$action             string  "DELETE"                   The default action of this execution ("DELETE", "BOUNCE", "RETRY" or "")
$errormsg           string  "5.7.1... we do not relay" The error message from the server
$errorcode          number  550                        The error code from the server (A value 0 of indicates network problems)
$errorndr           string  "5.7.1"                    The NDR code from the server (if available)
$transfertime       number  0.512                      The transfer time for this delivery attempt (seconds)
$sourceip           string  "10.0.0.1"                 The delivery source IP
$serverip           string  "172.16.1.25"              IP which we tried to connect to (empty on DNS problems)
$serverport         number  25                         Port which we tried to connect to
=================== ======= ========================== ===========

These are the writable pre-defined variables available.

================= ======= ===========
Variable          Type    Description
================= ======= ===========
$context          any     This variable is only defined if the pre-delivery context has been executed
================= ======= ===========

Functions
---------

.. function:: Bounce()

  Delete the message from the queue, and generating a DSN (bounce) to the sender.

  :return: doesn't return, script is terminated

  .. warning::

     If the message was delivered (``$action == ""``) this function will raise a runtime error.

.. function:: Delete()

  Delete the message from the queue, without generating a DSN (bounce) to the sender.

  :return: doesn't return, script is terminated

  .. warning::

     If the message was delivered (``$action == ""``) this function will raise a runtime error.

.. function:: Retry([options])

  Retry the message again later. This is the default action for non-permanent (5XX) ``$errorcode``'s. If the maximum retry count is exceeded; the message is either bounced or deleted depending on the transport's settings.

  :param array options: options array
  :return: doesn't return, script is terminated

  The following options are available in the options array.

   * **delay** (number) the delay in seconds. The default is according to the current transports retry delay.
   * **reason** (string) optional message to be logged with the message.
   * **increment_retry** (boolean) if the retry count should be increased. The default is ``true``.
   * **reset_retry** (boolean) if the retry count should be reset to zero. The default is ``false``.
   * **transportid** (string) set the transport ID. The default is ``$transportid``.

  .. warning::

     If the message was delivered (``$action == ""``) this function will raise a runtime error.

.. function:: SetDSN(options)

  Set the DSN options for the current delivery attempt if a DSN were to be created. It is not remembered for the next retry.

  :param array options: options array
  :rtype: none

  The following options are available in the options array.

   * **transportid** (string) Set the transport ID. The default is either choosen by the transport or automatically assigned.
   * **recipient** (string) Set the recipient. The default is ``$recipientlocalpart`` at ``$recipientdomain``.
   * **metadata** (array) Add additional metadata (KVP) to the DSN.
   * **from** (string) Set the From-header address of the DSN.
   * **from_name** (string) Set the From-header display name of the DSN.
   * **dkim** (array) Set the DKIM options of the DSN (``selector``, ``domain``, ``key`` including the options available in :func:`MIME.signDKIM`).

.. function:: SetMetaData(metadata)

  This function updates the queued message's metadata in the database. It is consequentially remembered for the next retry.
  The metadata must be an array with both string keys and values.

  :param array metadata: metadata to set
  :rtype: none

  .. note::

    To work-around the data type limitation of the metadata; data can be encoded using :func:`json_encode`.

.. function:: GetTLS([options])

  Get the TLS information for the delivery attempt.

  :param array options: options array
  :rtype: array

  The following options are available in the options array.

   * **fingerprint** (string) Generate the fingerprint of the certificate using one of the following hash function (``md5``, ``sha1``, ``sha256`` or ``sha512``). The default no hashing.

  The following items are available in the result.

   * **started** (boolean) If STARTTLS was issued.
   * **protocol** (string) The protocol used (eg. ``TLSv1.2``)
   * **cipher** (string) The cipher used (eg. ``ECDHE-RSA-AES256-SHA384``).
   * **keysize** (number) The keysize used (eg. ``256``).
   * **peer_cert** (array) The peer certificate (if requested by :func:`predelivery.SetTLS`). Same format as :func:`TLSSocket.getpeercert`.
   * **tlsrpt** (string) TLS reporting result.

.. function:: GetMetaData()

  Get the metadata set by :func:`SetMetaData`. If no data was set, an empty array is returned.

  :return: the data set by :func:`SetMetaData`
  :rtype: array

.. function:: GetMailFile([options])

  Return a :class:`File` class to the current mail file.

  :param array options: an options array
  :return: A File class to the current mail file.
  :rtype: File

  The following options are available in the options array.

   * **changes** (boolean) Include changes done to the original message. The default is ``false``.

On script error
---------------

On script error the default action is taken.

On implicit termination
-----------------------

If not explicitly terminated then the default action is taken.
