$(document).ready(function() {
        $("#lector").on("change", function(e) {
            if (e.target.files && e.target.files.length) {
                decode(URL.createObjectURL(e.target.files[0]));
            }
        });
        
        Quagga.onDetected(function(result) {
            //console.log(result);
            let code = result[0].codeResult.code;
            alert(code);
            $("#lector").val('');
        });
    });
    
    function decode(src){
        let config = {
            inputStream: {
                size: 800,
                singleChannel: false
            },
            locator: {
                patchSize: "large",
                halfSample: false
            },
            decoder: {
                readers: [
                    {
                        format: "code_128_reader",
                        config: {}
                    },
                    {
                        format: "ean_reader",
                        config: {}
                    }
                ],
                multiple: true
            },
            locate: true,
            src: src
        };
        
        Quagga.decodeSingle(config, function(result) {});
    }