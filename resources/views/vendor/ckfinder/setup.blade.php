<script type="text/javascript" src="{{ asset('js/ckfinder/ckfinder.js') }}"></script>
<!-- <script>CKFinder.config( { connectorPath: @json(route('ckfinder_connector')) } );</script> -->
<script>CKFinder.config( { connectorPath: "{{ url('/ckfinder/connector') }}" } );</script>
