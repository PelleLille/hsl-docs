.. module:: rcptto

RCPT TO
=======

The ``RCPT TO`` script allows verification of recipients.

Variables
---------

========================== ======= ========= ===========
Variable                   Type    Read-only Description
========================== ======= ========= ===========
:ref:`$arguments <v_a5>`   array   yes       Context/hook arguments
:ref:`$connection <v_c5>`  array   yes       Connection/session bound
:ref:`$transaction <v_t5>` array   yes       Transaction bound
$context                   any     no        Connection bound user-defined (default none)
========================== ======= ========= ===========

.. _v_a5:

Arguments
+++++++++

=================== ======= ========================== ===========
Array item          Type    Example                    Description
=================== ======= ========================== ===========
recipient           string  "test\@example.com"        Email address of recipient (envelope), lowercase
recipientlocalpart  string  "test"                     Local part of recipient's address (envelope)
recipientdomain     string  "example.com"              Domain part of recipient's address (envelope)
recipientparams     array   ["NOTIFY" => "NEVER", .. ] Recipient parameters to the envelope address
transportid         string  "mailtransport\:1"         ID of the transport profile to be used
=================== ======= ========================== ===========

.. _v_c5:

.. include:: var_connection.rst

.. _v_t5:

.. include:: var_transaction.rst

Functions
---------

.. function:: Accept([options])

  Accept the `RCPT TO` command (recipient).
  Optionally change the recipient accepted and its transport, which is written back to ``$transaction`` for subsequent executions.

  :param array options: an options array
  :return: doesn't return, script is terminated

  The following options are available in the options array.

   * **recipient** (string or array) Change the recipient email address, either as a string or a tuple with localpart and domain. The default is the ``recipientlocalpart`` argument `at` the ``recipientdomain`` argument.
   * **transportid** (string) Change the transport ID. The default is the ``transportid`` argument.
   * **reason** (string) The reason to report. The default is a system generated message.
   * **reply_codes** (array) The array may contain *code* (number) and *enhanced* (array of three numbers). The default is pre-defined.

.. function:: Reject([reason, [options]])

  Reject the `RCPT TO` command (recipient) with a permanent (554) error.

  :param reason: reject message with reason
  :type reason: string or array
  :param array options: an options array
  :return: doesn't return, script is terminated

  The following options are available in the options array.

   * **disconnect** (boolean) Disconnect the client. The default is ``false``.
   * **reply_codes** (array) The array may contain *code* (number) and *enhanced* (array of three numbers). The default is pre-defined.

.. function:: Defer([reason, [options]])

  Defer the `RCPT TO` command (recipient) with a temporary (450) error.

  :param reason: defer message with reason
  :type reason: string or array
  :param array options: an options array
  :return: doesn't return, script is terminated

  The following options are available in the options array.

   * **disconnect** (boolean) Disconnect the client. The default is ``false``.
   * **reply_codes** (array) The array may contain *code* (number) and *enhanced* (array of three numbers). The default is pre-defined.

.. function:: GetMailQueueMetric([options])

  Return metric information about the mail queue, it can be used to enforce quotas.

  :param array options: options array
  :rtype: number

.. include:: func_getmailqueuemetric.rst

.. include:: func_gettls.rst

On script error
---------------

On script error :func:`Defer` is called.

On implicit termination
-----------------------

If not explicitly terminated then :func:`Accept` is called.
