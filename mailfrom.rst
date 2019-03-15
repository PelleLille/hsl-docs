.. module:: mailfrom

MAIL FROM
=========

The ``MAIL FROM`` script allows verification of the sender.

Variables
---------

========================== ======= ========= ===========
Variable                   Type    Read-only Description
========================== ======= ========= ===========
:ref:`$arguments <v_a4>`   array   yes       Context/hook arguments
:ref:`$connection <v_c4>`  array   yes       Connection/session bound
:ref:`$transaction <v_t4>` array   yes       Transaction bound
$context                   any     no        Connection bound user-defined (default none)
========================== ======= ========= ===========

.. _v_a4:

Arguments
+++++++++

================= ======= ========================== ===========
Array item        Type    Example                    Description
================= ======= ========================== ===========
sender            string  "test\@example.org"        Email address of sender (envelope), lowercase
senderlocalpart   string  "test"                     Local part of sender's address (envelope)
senderdomain      string  "example.org"              Domain part of sender's address (envelope)
senderparams      array   ["SIZE" => "2048", ... ]   Sender parameters to the envelope address
================= ======= ========================== ===========

.. _v_c4:

.. include:: var_connection.rst

.. _v_t4:

Transaction
+++++++++++

========================= ======= ========================== ===========
Array item                Type    Example                    Description
========================= ======= ========================== ===========
id                        string  "18c190a3-93f-47d7-bd..."  ID of the transaction
========================= ======= ========================== ===========

Functions
---------

.. function:: Accept([options])

  Accept the `MAIL FROM` command (sender).
  Optionally change the sender accepted, which is written back to ``$transaction``.

  :param array options: an options array
  :return: doesn't return, script is terminated

  The following options are available in the options array.

   * **sender** (string or array) Change the sender email address, either as a string or a tuple with localpart and domain. The default is the ``senderlocalpart`` argument `at` the ``senderdomain`` argument.
   * **reason** (string) The reason to report. The default is a system generated message.
   * **reply_codes** (array) The array may contain *code* (number) and *enhanced* (array of three numbers). The default is pre-defined.

  .. note::

  	This function changes the sender for all recipients. To change sender per recipient use :func:`~predelivery.SetSender` in the :doc:`Pre-delivery <predelivery>` context.

.. function:: Reject([reason, [options]])

  Reject the `MAIL FROM` command (sender) with a permanent (554) error.

  :param reason: reject message with reason
  :type reason: string or array
  :param array options: an options array
  :return: doesn't return, script is terminated

  The following options are available in the options array.

   * **disconnect** (boolean) Disconnect the client. The default is ``false``.
   * **reply_codes** (array) The array may contain *code* (number) and *enhanced* (array of three numbers). The default is pre-defined.

.. function:: Defer([reason, [options]])

  Defer the `MAIL FROM` command (sender) with a temporary (450) error.

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
