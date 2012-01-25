<h1>Setting up the blacklist mail service</h1>

<p>This service is in charge of storing the list of mails of people that don't want to receive mails from you anymore (they
want to be blacklisted).
It provides an easy user interface right into Mouf to check the list of people that asked for being blacklisted.</p>

<p>You will need a valid datasource. This install wizard will create 1 table: "outgoing_mail_blacklist".</p>

<form action="configure">
	<button>Configure blacklist mail service</button>
</form>
<form action="skip">
	<button>Skip</button>
</form>