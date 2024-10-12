function cropMain () {
    var mc = $('#cropper');
    mc.croppie({
        viewport: {
            width: 150,
            height: 150,
            type: 'circle'
        },
        boundary: {
            width: 300,
            height: 300
        },
        // url: 'demo/demo-1.jpg',
        // enforceBoundary: false
        // mouseWheelZoom: false
    });
    mc.on('update.croppie', function (ev, data) {
        // console.log('jquery update', ev, data);
    });
    $('.js-main-image').on('click', function (ev) {
        mc.croppie('result', {
            type: 'rawcanvas',
            circle: true,
            // size: { width: 300, height: 300 },
            format: 'png'
        }).then(function (canvas) {
            popupResult({
                src: canvas.toDataURL()
            });
        });
    });
}