angular.module("dateService", [])
.service("DateService", function() {
	function parseShim(dateString) {
		var dateTime = dateString.split(/[T\s]/);
		var date = dateTime[0].split("-");
		var time = dateTime[1].split(":");

		var parsedDate = new Date(
			date[0], (date[1]-1), date[2],
			time[0], time[1], time[2]
		);

		return parsedDate;
	}

	this.parseDate = function(dateString) {
		var parsedDate = new Date(dateString);
		if(isNaN(parsedDate)) {
			parsedDate = parseShim(dateString);
		}

		return parsedDate;
	};
});