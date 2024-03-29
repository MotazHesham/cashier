<div class="card">
    <div class="card-header">

    </div>

    <div class="card-body text-center">
      <section class="container" id="cam-content">
        @if(config('app.env') == 'local')
            <input type="text" name="code" value="" class="form-control" placeholder="user_id" id="user_id">
            <button type="button" class="btn btn-dark" name="button" onclick="submit_in_local()">Next</button>
        @else
              <div class="mb-3">
                  <button class="btn btn-pill btn-lg btn-success" id="startButton">Start</button>
                  <button class="btn btn-pill btn-lg btn-info " id="resetButton">Stop</button>
              </div>

              <div>
                  <video id="video" width="300" height="200" style="border: 1px solid gray"></video>
              </div>

              <div id="sourceSelectPanel" style="display:none">
                  <span for="sourceSelect">Change video source:</span>
                  <select id="sourceSelect" style="max-width:400px">
                  </select>
              </div>

              <div style="display: none" class="text-center">
                  <span for="decoding-style"> Decoding Style:</span>
                  <select id="decoding-style" size="1">
                      <option value="once">Decode once</option>
                      <option value="continuously">Decode continuously</option>
                  </select>
              </div>

              <span>Result:</span>
              <pre><code id="result"></code></pre>
        @endif
    </section>
    </div>
</div>


@if(config('app.env') != 'local')
<script type="text/javascript" src="https://unpkg.com/@zxing/library@latest/umd/index.min.js"></script>
@endif
<script type="text/javascript">
    @if(config('app.env') != 'local')
        load_cam();
    @endif

    function submit_in_local(){
        $.ajax({
            type: "POST",
            url: '{{ route('admin.orders.qr_output') }}',
            data: {_token: '{{ csrf_token() }}', code: $('#user_id').val(),user_id:'{{$user_id}}',order_id:'{{$order_id}}'},
            success: function(data) {
                //console.log(data);

                if (data.status == true) {
                    $('#qr_user_id').val(data.user_id);
                    $('#cam-content').html(data.message);
                } else {
                    $('#cam-content').html(data.message);
                }
            }
        });
    }
    function decodeOnce(codeReader, selectedDeviceId) {
        codeReader.decodeFromInputVideoDevice(selectedDeviceId, 'video').then((result) => {
            console.log(result.text)
            $.post('{{ route('admin.orders.qr_output') }}', {
                _token: '{{ csrf_token() }}',
                code: result.text,
                user_id:'{{$user_id}}',
                order_id:'{{$order_id}}',
            }, function(data) {
                console.log(data);

                if (data.status == true) {
                    $('#qr_user_id').val(data.user_id);
                    $('#cam-content').html(data.message);
                } else {
                    $('#cam-content').html(data.message);
                }
            });
        }).catch((err) => {
            console.error(err)
            document.getElementById('result').textContent = err
        })
    }


    function load_cam() {

        let selectedDeviceId;
        const codeReader = new ZXing.BrowserQRCodeReader();
        console.log('ZXing code reader initialized');

        const decodingStyle = document.getElementById('decoding-style').value;

        decodeOnce(codeReader, selectedDeviceId);

        console.log(`Started decode from camera with id ${selectedDeviceId}`)

        codeReader.getVideoInputDevices()
            .then((videoInputDevices) => {

                const sourceSelect = document.getElementById('sourceSelect')
                selectedDeviceId = videoInputDevices[0].deviceId

                if (videoInputDevices.length >= 1) {
                    videoInputDevices.forEach((element) => {
                        const sourceOption = document.createElement('option')
                        sourceOption.text = element.label
                        sourceOption.value = element.deviceId
                        sourceSelect.appendChild(sourceOption)
                    })

                    sourceSelect.onchange = () => {
                        selectedDeviceId = sourceSelect.value;
                    };

                    const sourceSelectPanel = document.getElementById('sourceSelectPanel')
                    sourceSelectPanel.style.display = 'block'
                }

                document.getElementById('startButton').addEventListener('click', () => {
                    const decodingStyle = document.getElementById('decoding-style').value;

                    decodeOnce(codeReader, selectedDeviceId);

                    console.log(`Started decode from camera with id ${selectedDeviceId}`)
                })

                document.getElementById('resetButton').addEventListener('click', () => {
                    codeReader.reset()
                    document.getElementById('result').textContent = '';
                    console.log('Reset.')
                })

            })

            .catch((err) => {
                console.error(err)
            })

    }
</script>
