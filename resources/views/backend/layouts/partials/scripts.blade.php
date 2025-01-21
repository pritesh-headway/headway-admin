<!-- jquery latest version -->
<script src="{{ asset('backend/assets/js/vendor/jquery-2.2.4.min.js') }}"></script>
<!-- bootstrap 4 js -->
<script src="{{ asset('backend/assets/js/popper.min.js') }}"></script>
<script src="{{ asset('backend/assets/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('backend/assets/js/owl.carousel.min.js') }}"></script>
<script src="{{ asset('backend/assets/js/metisMenu.min.js') }}"></script>
<script src="{{ asset('backend/assets/js/jquery.slimscroll.min.js') }}"></script>
<script src="{{ asset('backend/assets/js/jquery.slicknav.min.js') }}"></script>

<!-- start chart js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.min.js"></script>
<!-- start highcharts js -->
<script src="https://code.highcharts.com/highcharts.js"></script>
<!-- start zingchart js -->
<script src="https://cdn.zingchart.com/zingchart.min.js"></script>
<script>
    zingchart.MODULESDIR = "https://cdn.zingchart.com/modules/";
ZC.LICENSE = ["569d52cefae586f634c54f86dc99e6a9", "ee6b7db5b51705a13dc2339db3edaf6d"];
</script>
<!-- all line chart activation -->
<script src="{{ asset('backend/assets/js/line-chart.js') }}"></script>
<!-- all pie chart -->
<script src="{{ asset('backend/assets/js/pie-chart.js') }}"></script>
<!-- others plugins -->
<script src="{{ asset('backend/assets/js/plugins.js') }}"></script>
<script src="{{ asset('backend/assets/js/scripts.js') }}"></script>

{{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script> --}}
<script src="https://momentjs.com/downloads/moment.min.js"></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.1.0/fullcalendar.js'></script>
<link rel='stylesheet' href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.1.0/fullcalendar.min.css" />
<script>
    $(document).ready(function() {
        $('#calendar').fullCalendar({
            editable: true,
            selectable: true,
            events: '/events',
            select: function(start, end) {
                var title = prompt('Enter Event Title');
                if (title) {
                    var eventData = {
                        title: title,
                        start: moment(start).format(),
                        end: moment(end).format(),
                    };
                    $.ajax({
                        url: '/events',
                        type: 'POST',
                        data: JSON.stringify(eventData),
                        contentType: 'application/json',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function() {
                            $('#calendar').fullCalendar('refetchEvents');
                            alert('Event created successfully');
                        }
                    });
                }
                $('#calendar').fullCalendar('unselect');
            }
        });
    });
</script>