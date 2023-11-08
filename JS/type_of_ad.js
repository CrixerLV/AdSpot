document.addEventListener('DOMContentLoaded', function () {
    var adTypeSelect = document.getElementById('adType');
    var vehicleTypeSection = document.getElementById('vehicleTypeSection');
    var vehicleTypeSelect = document.getElementById('vehicleType');
    var vehicleBrandSection = document.getElementById('vehicleBrandSection');
    var vehicleBrandSelect = document.getElementById('vehicleBrand');

    adTypeSelect.addEventListener('change', function () {
        var selectedOption = adTypeSelect.value;
        if (selectedOption === 'Vehicle') {
            vehicleTypeSection.style.display = 'block';
        } else {
            vehicleTypeSection.style.display = 'none';
            vehicleBrandSection.style.display = 'none';
        }
    });

    vehicleTypeSelect.addEventListener('change', function () {
        var selectedVehicleType = vehicleTypeSelect.value;
        if (selectedVehicleType === 'Cars') {
            var carBrands = [
                'Audi', 'BMW', 'Mercedes', 'Volvo', 'Toyota', 'Honda', 'Ford', 'Volkswagen',
                'Chevrolet', 'Nissan', 'Hyundai', 'Subaru', 'Mazda', 'Lexus', 'Kia', 'Jeep',
                'Tesla', 'Ferrari', 'Porsche', 'Jaguar', 'Land Rover', 'Maserati', 'Aston Martin'
            ];
            
    
            vehicleBrandSelect.innerHTML = '';
    
            carBrands.forEach(function (brand) {
                var option = document.createElement('option');
                option.value = brand;
                option.textContent = brand;
                vehicleBrandSelect.appendChild(option);
            });

            vehicleBrandSection.style.display = 'block';
        } else if (selectedVehicleType === 'Heavy') {
            var heavyBrands = [
                'Caterpillar', 'Komatsu', 'John Deere', 'Liebherr', 'Hitachi', 'Terex', 'Volvo CE',
                'SANY', 'Doosan', 'XCMG', 'Kobelco', 'JCB', 'Case', 'Bomag', 'Wirtgen', 'SDLG'
            ];
    
            vehicleBrandSelect.innerHTML = '';
    
            heavyBrands.forEach(function (brand) {
                var option = document.createElement('option');
                option.value = brand;
                option.textContent = brand;
                vehicleBrandSelect.appendChild(option);
            });
            vehicleBrandSection.style.display = 'block';
        }
        else if (selectedVehicleType === 'Motorcycles') {
            var motorcycleBrands = [
                'Harley-Davidson', 'Honda', 'Yamaha', 'Kawasaki', 'Suzuki', 'Ducati', 'BMW Motorrad',
                'Triumph', 'KTM', 'Indian Motorcycle', 'Aprilia', 'Moto Guzzi', 'Piaggio', 'Royal Enfield'
            ];
    
            vehicleBrandSelect.innerHTML = '';
    
            motorcycleBrands.forEach(function (brand) {
                var option = document.createElement('option');
                option.value = brand;
                option.textContent = brand;
                vehicleBrandSelect.appendChild(option);
            });
            vehicleBrandSection.style.display = 'block';
        }
        else if (selectedVehicleType === 'WaterTransports') {
            var boatBrands = [
                'Boston Whaler', 'Grady-White', 'Yamaha Boats', 'Sea Ray', 'Malibu Boats', 'MasterCraft',
                'Beneteau', 'Hobie Cat', 'Lund Boats', 'Sunseeker', 'Regal Boats', 'Azimut Yachts',
                'Bertram', 'Cobalt Boats', 'Formula Boats', 'Jeanneau', 'Sun Tracker', 'Tracker Boats'
            ];
    
            vehicleBrandSelect.innerHTML = '';
    
            boatBrands.forEach(function (brand) {
                var option = document.createElement('option');
                option.value = brand;
                option.textContent = brand;
                vehicleBrandSelect.appendChild(option);
            });
            vehicleBrandSection.style.display = 'block';
        } else if (selectedVehicleType === 'FarmEquipment') {
            var farmEquipmentBrands = [
                'John Deere', 'Case IH', 'New Holland', 'Kubota', 'Massey Ferguson', 'AGCO', 'Mahindra',
                'Fendt', 'Challenger', 'Versatile', 'JCB', 'Valtra', 'Claas', 'McCormick', 'Same Deutz-Fahr'
            ];
    
            vehicleBrandSelect.innerHTML = '';
    
            farmEquipmentBrands.forEach(function (brand) {
                var option = document.createElement('option');
                option.value = brand;
                option.textContent = brand;
                vehicleBrandSelect.appendChild(option);
            });
            vehicleBrandSection.style.display = 'block';
        }else{
            vehicleBrandSection.style.display = 'none';
        }
    });
    
});