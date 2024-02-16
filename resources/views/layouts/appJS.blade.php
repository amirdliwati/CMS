<script type="text/javascript">
    //Pace.on('done', function() { $('#overlay').hide(); });

    function GoToPage(url){window.open(url, "_self");}
    function RefreshPage(){location.reload();}

    function NotifyMessage(title, message, type) {
        $.notify({
            title: title,
            message: message
        },
        {
            type: type,
            allow_dismiss:true,
            newest_on_top:true ,
            mouse_over:true,
            showProgressbar:false,
            spacing:10,
            timer:2000,
            placement:{
                from:'bottom',
                align:'right'
            },
            offset:{
                x:30,
                y:30
            },
            delay:1000 ,
            z_index:10000,
            animate:{
                enter:'animated bounce',
                exit:'animated bounce'
            }
        });
    }

    function SwalMessage(title, message, type) {
        swal({
            title: title,
            text: message,
            type: type,
            showCancelButton: false,
            confirmButtonClass: 'btn btn-primary-gradien',
            confirmButtonText: 'OK'
        });
    }
</script>

<!-- Notify -->
@if(Session::has('msgSuccess'))
    <script type="text/javascript"> NotifyMessage('Success','{{Session::get("msgSuccess")}}','success'); </script>
    {{Session::forget("msgSuccess")}}

@elseif(Session::has('msgInfo'))
    <script type="text/javascript"> NotifyMessage('Info','{{Session::get("msgInfo")}}','info'); </script>
    {{Session::forget("msgInfo")}}

@elseif(Session::has('msgDanger'))
    <script type="text/javascript"> NotifyMessage('Error','{{Session::get("msgDanger")}}','danger'); </script>
    {{Session::forget("msgDanger")}}

@elseif(Session::has('msgPrimary'))
    <script type="text/javascript"> NotifyMessage('Done','{{Session::get("msgPrimary")}}','primary'); </script>
    {{Session::forget("msgPrimary")}}

@elseif(Session::has('msgWarning'))
    <script type="text/javascript"> NotifyMessage('Warning','{{Session::get("msgWarning")}}','warning'); </script>
    {{Session::forget("msgWarning")}}

@elseif(Session::has('msgSecondary'))
    <script type="text/javascript"> NotifyMessage('Not Done','{{Session::get("msgSecondary")}}','secondary'); </script>
    {{Session::forget("msgSecondary")}}
@endif
