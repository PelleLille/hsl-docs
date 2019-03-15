.. module:: eodonce

Per message
===========

The per-message end-of-DATA script is executed once, when the message is fully received (but not yet accepted).
To relay the message for all recipients, call :func:`Queue` for each ``$transaction["recipients"]`` and then :func:`Accept`.

Variables
---------

These are the read-only pre-defined variables available.

========================== ======= ========= ===========
Variable                   Type    Read-only Description
========================== ======= ========= ===========
:ref:`$arguments <v_a6>`   array   yes       Context/hook arguments
:ref:`$connection <v_c6>`  array   yes       Connection/session bound
:ref:`$transaction <v_t6>` array   yes       Transaction bound
$context                   any     no        Connection bound user-defined (default none)
========================== ======= ========= ===========

.. _v_c6:

.. include:: var_connection.rst

.. _v_t6:

.. include:: var_transaction.rst

Functions
---------

* **Actions** :func:`Accept` :func:`Reject` :func:`Defer`
* **Queueing** :func:`Queue` :func:`History`
* **DATA, MIME and attachments** :func:`GetMailMessage` :cpp:class:`MailMessage` :cpp:class:`MIMEPart` 
* **Embedded scanning** :func:`ScanDMARC` :func:`ScanDLP` :func:`ScanRPD` :func:`ScanSA` :func:`ScanKAV` :func:`ScanCLAM`
* **Miscellaneous** :func:`GetMailQueueMetric` :func:`GetTLS`

Actions
^^^^^^^

.. function:: Accept([options])

  Accept the `DATA` command (mail data).

  :return: doesn't return, script is terminated

  The following options are available in the options array.

   * **reason** (string) The reason to report. The default is a system generated message.
   * **reply_codes** (array) The array may contain *code* (number) and *enhanced* (array of three numbers). The default is pre-defined.

.. function:: Reject([reason, [options]])

  Reject (550) a message. If `reason` is an array or contains `\\n` it will be split into a multiline response.

  :param reason: reject message with reason
  :type reason: string or array
  :param array options: an options array
  :return: doesn't return, script is terminated

  The following options are available in the options array.

   * **disconnect** (boolean) Disconnect the client. The default is ``false``.
   * **reply_codes** (array) The array may contain *code* (number) and *enhanced* (array of three numbers). The default is pre-defined.

.. function:: Defer([reason, [options]])

  Defer (421) a message. If `reason` is an array or contains `\\n` it will be split into a multiline response.

  :param reason: defer message with reason
  :type reason: string or array
  :param array options: an options array
  :return: doesn't return, script is terminated

  The following options are available in the options array.

   * **disconnect** (boolean) Disconnect the client. The default is ``false``.
   * **reply_codes** (array) The array may contain *code* (number) and *enhanced* (array of three numbers). The default is pre-defined.

Queueing
^^^^^^^^

.. function:: Queue(recipient, transportid, [options])

  Queue the message.

  :param recipient: the recipient email address, either as a string or a tuple with localpart and domain
  :type recipient: string or array
  :param string transportid: the transport profile ID
  :param array options: an options array
  :return: true (or none)
  :rtype: boolean or none

  The following options are available in the options array.

   * **sender** (string) The sender email address, either as a string or a tuple with localpart and domain. The default is ``$transaction["senderlocalpart"]`` `at` ``$transaction["senderdomain"]``.
   * **metadata** (array) Add metadata to the queued message, as a key-value pair array of strings.
   * **hold** (boolean) Put the message in the hold (inactive) queue.
   * **delay** (number) Delay the first delivery attempt, in seconds. The default is ``0``.

.. function:: History(action, recipient, [options])

  Add an entry to the history database table.
  This function is only available in the full system distribution (virtual machine) package.
  For long-term logging in high volume systems, remote logging to an external database such as Elasticsearch is recommended.

  :param string action: the logged action; either of `REJECT`, `DELETE`, `DELIVER`, `DEFER` or `ERROR`
  :param recipient: the recipient email address, either as a string or a tuple with localpart and domain
  :type recipient: string or array
  :param array options: an options array
  :return: true (or none)
  :rtype: boolean or none

  The following options are available in the options array.

   * **sender** (string) the sender email address, either as a string or a tuple with localpart and domain. The default is ``$transaction["senderlocalpart"]`` `at` ``$transaction["senderdomain"]``.
   * **metadata** (array) add metadata to the history entry, as a key-value pair array of strings
   * **transportid** (string) the transport profile ID
   * **reason** (string) reason message

.. include:: func_eod.rst

On script error
---------------

On script error :func:`Defer` is called.

On implicit termination
-----------------------

If not explicitly terminated then :func:`Defer` is called.
