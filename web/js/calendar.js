$(document).ready(function() {

    // page is now ready, initialize the calendar...

	alert('lkjl');
    $('#calendar').fullCalendar({
        // put your options and callbacks here
    	timeFormat: 'H(:mm)',
    	lang: 'fr',
    	events: [
    	         {
    	        	 allDay: true,
    	             start: '2016-02-14T22:00:22',
    	             title: 'kljze',
    	             color: 'green',
    	             editable:true,
    	             url: '#',
    	             textColor: 'black',
    	         },
    	         {
    	        	 allDay: true,
    	             start: '2016-02-18',
    	             title: 'kljze',
    	             color: 'red',
    	             editable:true,
    	             url: '#',
    	             textColor: 'black',
    	         }
    	     ]
    })

});