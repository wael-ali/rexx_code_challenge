# Data Base:
tables:
    * user (name, email)
    * participation (fee)
    * event (date, name)
relations:
    * user 1 --------> m participation
    * participation 1 --------> 1 user

    * event 1 --------> m participation
    * participation 1 --------> 1 event
