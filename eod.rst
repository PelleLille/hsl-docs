End-of-DATA
===========

The end-of-DATA (EOD) script is executed after the dot ``.``, when the message is fully received (but not yet accepted).
There are two sub-types of EOD; one which is executed once per message (DATA command), and other that is executed for each recipient.

.. toctree::

    eodonce
    eodrcpt
