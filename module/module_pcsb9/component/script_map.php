<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD_3uR-M8yPx3Tv8DAgbenP2-vJfxzxSD8&callback=initMap" async defer></script>

<script>
        // ฟังก์ชันที่จะถูกเรียกเมื่อโหลดแผนที่
        function initMap() {
            // สร้างตัวแปรที่มีพิกัดเริ่มต้น
            var myLatLng = { lat: 13.581975683377241, lng: 100.28943314999081 };

            // สร้างแผนที่และกำหนดตำแหน่งเริ่มต้น
            var map = new google.maps.Map(document.getElementById('map_canvas'), {
                center: myLatLng,
                zoom: 15,
                mapTypeId: 'satellite'
            });

            // สร้างตัวแปรที่เก็บเครื่องหมายบนแผนที่
            var marker = new google.maps.Marker({
                position: myLatLng,
                map: map,
                title: 'PCS Warehouse 8'
            });
        }
    </script>
