.. module:: postdelivery

Post-delivery
=============

The post-delivery script is executed after a delivery attempt or when a message is deleted or bounced from the queue. If the message was deleted from the queue (either manually or by retention) the ``$context`` variable will not be defined.

Variables
---------

These are the pre-defined variables available.

========================== ======= ========= ===========
Variable                   Type    Read-only Description
========================== ======= ========= ===========
:ref:`$arguments <v_a1>`   array   yes       Context/hook arguments
:ref:`$message <v_m1>`     array   yes       The queued message (with modifications from pre-delivery)
$context                   any     no        This variable is only defined if the pre-delivery has been executed
========================== ======= ========= ===========

.. _v_a1:

Arguments
+++++++++

=================== ======= ================================= ===========
Array item          Type    Example                           Description
=================== ======= ================================= ===========
retry               number  3                                 The current retry
action              string  "DELETE"                          The default action of this execution ("DELETE", "BOUNCE", "QUEUE"). Missing on successful deliveries.
:ref:`attempt <r1>` array   ["result" => [ "code" => 200, ... The delivery attempt result (if an attempt was made)
=================== ======= ================================= ===========

.. _r1:

Attempt
>>>>>>>

======================= ======= ======================================= ===========
Array item              Type    Example                                 Description
======================= ======= ======================================= ===========
:ref:`result <ar1>`     array   ["code" = >250, "enhanced" => [1, ...]  A SMTP protocol response (if available)
:ref:`error <ae1>`      array   ["temporary" => true, "reason" => ...]  A generic eror message (if no SMTP result)
:ref:`connection <ac1>` array   ["localip" => "1.2.3.4", "remoteip"...] Connection information
duration                number  1.012                                   The delivery attempt duration
======================= ======= ======================================= ===========

.. _ar1:

Result
______

==================== ======= ========================== ===========
Array item           Type    Example                    Description
==================== ======= ========================== ===========
code                 number  250                        A SMTP status code
enhanced             array   [2, 0, 0]                  A SMTP enhanced status code
reason               array   ["Ok: queued as 18c19..."] A SMTP response text
:ref:`state <as1>`   string  "MAIL"                     An enum to indicate which issued SMTP command triggerd the result
==================== ======= ========================== ===========

.. _ae1:

Error
_____

==================== ======= ========================== ===========
Array item           Type    Example                    Description
==================== ======= ========================== ===========
temporary            boolean true                       If the error may be transient 
message              message "A generic error"          An error message
==================== ======= ========================== ===========

.. _ac1:

Connection
__________

==================== ======= ========================== ===========
Array item           Type    Example                    Description
==================== ======= ========================== ===========
locaip               string  "1.2.3.4"                  The localip used
remoteip             string  "4.3.2.1"                  The remoteip used
remotemx             string  "mail.example.com"         The remotemx used
:ref:`tls <atls1>`   array   ["started" => true, ...]   TLS information (if TLS was started)
==================== ======= ========================== ===========

.. _atls1:

TLS
```

==================== ======= ========================== ===========
Array item           Type    Example                    Description
==================== ======= ========================== ===========
started              boolean true                       If STARTTLS was successfully started
protocol             string  "TLSv1.3"                  The protocol (if available)
cipher               string  "ECDHE-RSA-AES256-SHA384"  The cipher (if available)
keysize              number  256                        The keysize (if available)
:ref:`peercert <p1>` array                              The peer certificate (if available)
tlsrpt               string  "starttls"                 The tlsrpt error (if available)
==================== ======= ========================== ===========

.. _p1:

Peercert
''''''''

==================== ============= ========================== ===========
Array item           Type          Example                    Description
==================== ============= ========================== ===========
x509                 X509Resource                             An X509Resource to be used with the :class:`X509` class
==================== ============= ========================== ===========


.. _v_m1:

Message
+++++++

============================ ======= ========================== ===========
Array item                   Type    Example                    Description
============================ ======= ========================== ===========
id                           string  "18c190a3-93f-47d7-bd..."  ID of the transaction
serverid                     string  "inbound"                  ID of the server
sender                       string  "test\@example.org"        Sender address (envelope), lowercase
:ref:`senderaddress <z1>`    array   ["localpart" => "test"...] Sender address (envelope)
recipient                    string  "test\@example.org"        Recipient address (envelope), lowercase
:ref:`recipientaddress <z1>` array   ["localpart" => "test"...] Recipient address (envelope)
transportid                  string  "inbound"                  ID of the transport profile to be used
queueid                      number  12345                      Queue ID of the message
============================ ======= ========================== ===========

.. _z1:

Address
>>>>>>>

==================== ======= ========================== ===========
Array item           Type    Example                    Description
==================== ======= ========================== ===========
localpart            string  "test"                     Local part of address
domain               string  "example.org"              Domain part of address
==================== ======= ========================== ===========

Functions
---------

.. function:: Queue([options])

  Queue the message to be retried later. This is the default action for temporary / non-permanent (5XX) errors. If the maximum retry count is exceeded; the message is either bounced or deleted depending on the transport's settings.

  :param array options: options array
  :return: doesn't return, script is terminated

  The following options are available in the options array.

   * **hold** (boolean) Put the message in the hold (inactive) queue. The default is ``false``.
   * **delay** (number) the delay in seconds. The default is according to the current transports retry delay.
   * **reason** (string) optional message to be logged with the message.
   * **increment_retry** (boolean) if the retry count should be increased. The default is ``true``.
   * **reset_retry** (boolean) if the retry count should be reset to zero. The default is ``false``.
   * **transportid** (string) change the transport ID. The default the current `transportid`.

  .. warning::

     If the message was delivered (``isset($arguments["action"])``) this function will raise a runtime error.

.. function:: Bounce()

  Delete the message from the queue, and generating a DSN (bounce) to the sender.

  :return: doesn't return, script is terminated

  .. warning::

     If the message was delivered (``isset($arguments["action"])``) this function will raise a runtime error.

.. function:: Delete()

  Delete the message from the queue, without generating a DSN (bounce) to the sender.

  :return: doesn't return, script is terminated

  .. warning::

     If the message was delivered (``isset($arguments["action"])``) this function will raise a runtime error.

.. function:: SetDSN(options)

  Set the DSN options for the current delivery attempt if a DSN were to be created. It is not remembered for the next retry.

  :param array options: options array
  :rtype: none

  The following options are available in the options array.

   * **transportid** (string) Set the transport ID. The default is either choosen by the transport or automatically assigned.
   * **recipient** (string) Set the recipient. The default is the sender address.
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

.. function:: GetMetaData()

  Get the metadata set by :func:`SetMetaData`. If no data was set, an empty array is returned.

  :return: the data set by :func:`SetMetaData`
  :rtype: array

On script error
---------------

On script error the default action is taken.

On implicit termination
-----------------------

If not explicitly terminated then the default action is taken.

References
-----------------------

.. _as1:

SMTP states
+++++++++++

+-----------------+-------------------------------------------------+
| CONNECT         | The initial SMTP greeting                       |
+-----------------+-------------------------------------------------+
| HELO            |                                                 |
+-----------------+-------------------------------------------------+
| EHLO            |                                                 |
+-----------------+-------------------------------------------------+
| LHLO            |                                                 |
+-----------------+-------------------------------------------------+
| STARTTLS        |                                                 |
+-----------------+-------------------------------------------------+
| AUTH-CRAM-MD5   | In reply to sending AUTH CRAM-MD5 command       |
+-----------------+-------------------------------------------------+
| AUTH-PLAIN      | In reply to sending AUTH PLAIN command          |
+-----------------+-------------------------------------------------+
| AUTH-LOGIN      | In reply to sending AUTH LOGIN command          |
+-----------------+-------------------------------------------------+
| AUTH-LOGIN-USER | In reply to sending AUTH LOGIN username         |
+-----------------+-------------------------------------------------+
| AUTH            | In reply to last command of AUTH login attempt  |
+-----------------+-------------------------------------------------+
| XCLIENT         | In reply to sending a XCLIENT command           |
+-----------------+-------------------------------------------------+
| MAIL            |                                                 |
+-----------------+-------------------------------------------------+
| RCPT            |                                                 |
+-----------------+-------------------------------------------------+
| DATA            | In reply to sending the DATA command            |
+-----------------+-------------------------------------------------+
| EOD             | In reply sending the End-of-DATA                |
+-----------------+-------------------------------------------------+
| RSET            |                                                 |
+-----------------+-------------------------------------------------+
| NOOP            |                                                 |
+-----------------+-------------------------------------------------+
| QUIT            |                                                 |
+-----------------+-------------------------------------------------+