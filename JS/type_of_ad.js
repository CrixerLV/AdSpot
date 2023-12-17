document.addEventListener('DOMContentLoaded', function () {
    var adTypeSelect = document.getElementById('adType');

    var vehicleTypeSection = document.getElementById('vehicleTypeSection');
    var vehicleTypeSelect = document.getElementById('vehicleType');
    var vehicleBrandSection = document.getElementById('vehicleBrandSection');
    var vehicleBrandSelect = document.getElementById('vehicleBrand');

    var petTypeSection = document.getElementById('petTypeSection');
    var petTypeSelect = document.getElementById('petType');
    var petBrandSection = document.getElementById('petBrandSection');
    var petBrandSelect = document.getElementById('petBrand');

    var jobTypeSection = document.getElementById('JobTypeSection');
    var jobTypeSelect = document.getElementById('JobType');
    var jobBrandSection = document.getElementById('JobBrandSection');
    var jobBrandSelect = document.getElementById('JobBrand');

    var furnitureTypeSection = document.getElementById('FurnitureTypeSection');
    var furnitureTypeSelect = document.getElementById('FurnitureType');
    var furnitureBrandSection = document.getElementById('FurnitureBrandSection');
    var furnitureBrandSelect = document.getElementById('FurnitureBrand');

    var electronicTypeSection = document.getElementById('electronicTypeSection');
    var electronicTypeSelect = document.getElementById('electronicType');
    var electronicBrandSection = document.getElementById('electronicBrandSection');
    var electronicBrandSelect = document.getElementById('electronicBrand');

    var otherTypeSection = document.getElementById('otherTypeSection');
    var otherTypeSelect = document.getElementById('otherType');
    var otherBrandSection = document.getElementById('otherBrandSection');
    var otherBrandSelect = document.getElementById('otherBrand');

    adTypeSelect.addEventListener('change', function () {
        var selectedOption = adTypeSelect.value;
        if (selectedOption === 'Vehicle') {
            vehicleTypeSection.style.display = 'block';
            furnitureTypeSection.style.display = 'none';
            furnitureBrandSection.style.display = 'none';
            jobTypeSection.style.display = 'none';
            jobBrandSection.style.display = 'none';
            petTypeSection.style.display = 'none';
            petBrandSection.style.display = 'none';
            electronicTypeSection.style.display = 'none';
            electronicBrandSection.style.display = 'none';
        } else if (selectedOption === 'Pets') {
            vehicleTypeSection.style.display = 'none';
            vehicleBrandSection.style.display = 'none';
            furnitureTypeSection.style.display = 'none';
            furnitureBrandSection.style.display = 'none';
            jobTypeSection.style.display = 'none';
            jobBrandSection.style.display = 'none';
            petTypeSection.style.display = 'block';
            electronicTypeSection.style.display = 'none';
            electronicBrandSection.style.display = 'none';
        } else if (selectedOption === 'Job'){
            vehicleTypeSection.style.display = 'none';
            vehicleBrandSection.style.display = 'none';
            furnitureTypeSection.style.display = 'none';
            furnitureBrandSection.style.display = 'none';
            jobTypeSection.style.display = 'block';
            petTypeSection.style.display = 'none';
            petBrandSection.style.display = 'none';
            electronicTypeSection.style.display = 'none';
            electronicBrandSection.style.display = 'none';
        } else if (selectedOption === 'Electronic'){
            vehicleTypeSection.style.display = 'none';
            vehicleBrandSection.style.display = 'none';
            furnitureTypeSection.style.display = 'none';
            furnitureBrandSection.style.display = 'none';
            jobTypeSection.style.display = 'none';
            jobBrandSection.style.display = 'none';
            petTypeSection.style.display = 'none';
            petBrandSection.style.display = 'none';
            electronicTypeSection.style.display = 'block';
        }
        else if (selectedOption === 'Furniture'){
            vehicleTypeSection.style.display = 'none';
            vehicleBrandSection.style.display = 'none';
            jobTypeSection.style.display = 'none';
            jobBrandSection.style.display = 'none';
            petTypeSection.style.display = 'none';
            petBrandSection.style.display = 'none';
            electronicTypeSection.style.display = 'none';
            electronicBrandSection.style.display = 'none';
            furnitureTypeSection.style.display = "block"
        }
        else if (selectedOption === 'Other'){
            vehicleTypeSection.style.display = 'none';
            vehicleBrandSection.style.display = 'none';
            jobTypeSection.style.display = 'none';
            jobBrandSection.style.display = 'none';
            petTypeSection.style.display = 'none';
            petBrandSection.style.display = 'none';
            electronicTypeSection.style.display = 'none';
            electronicBrandSection.style.display = 'none';
            furnitureTypeSection.style.display = 'none';
            furnitureBrandSection.style.display = 'none';
            otherTypeSection.style.display = "block"
            otherBrandSection.style.display = "block"
        }
    });

    vehicleTypeSelect.addEventListener('change', function () {
        var selectedVehicleType = vehicleTypeSelect.value;

        if (selectedVehicleType === 'Cars') {
            var carBrands = ['Audi', 'BMW', 'Mercedes', 'Volvo', 'Toyota', 'Honda', 'Ford', 'Volkswagen', 'Chevrolet', 'Nissan', 'Hyundai', 'Subaru', 'Mazda', 'Lexus', 'Kia', 'Jeep', 'Tesla', 'Ferrari', 'Porsche', 'Jaguar', 'Land Rover', 'Maserati', 'Aston Martin', 'Cits'];
            populateVehicleBrands(carBrands);
        } else if (selectedVehicleType === 'Heavy') {
            var heavyBrands = ['Caterpillar', 'Komatsu', 'John Deere', 'Liebherr', 'Hitachi', 'Terex', 'Volvo CE', 'SANY', 'Doosan', 'XCMG', 'Kobelco', 'JCB', 'Case', 'Bomag', 'Wirtgen', 'SDLG', 'Cits'];
            populateVehicleBrands(heavyBrands);
        } else if (selectedVehicleType === 'Motorcycles') {
            var motorcycleBrands = ['Harley-Davidson', 'Honda', 'Yamaha', 'Kawasaki', 'Suzuki', 'Ducati', 'BMW Motorrad', 'Triumph', 'KTM', 'Indian Motorcycle', 'Aprilia', 'Moto Guzzi', 'Piaggio', 'Royal Enfield', 'Cits'];
            populateVehicleBrands(motorcycleBrands);
        } else if (selectedVehicleType === 'WaterTransport') {
            var boatBrands = ['Boston Whaler', 'Grady-White', 'Yamaha Boats', 'Sea Ray', 'Malibu Boats', 'MasterCraft', 'Beneteau', 'Hobie Cat', 'Lund Boats', 'Sunseeker', 'Regal Boats', 'Azimut Yachts', 'Bertram', 'Cobalt Boats', 'Formula Boats', 'Jeanneau', 'Sun Tracker', 'Tracker Boats', 'Cits'];
            populateVehicleBrands(boatBrands);
        } else if (selectedVehicleType === 'FarmEquipment') {
            var farmEquipmentBrands = ['John Deere', 'Case IH', 'New Holland', 'Kubota', 'Massey Ferguson', 'AGCO', 'Mahindra', 'Fendt', 'Challenger', 'Versatile', 'JCB', 'Valtra', 'Claas', 'McCormick', 'Same Deutz-Fahr', 'Cits'];
            populateVehicleBrands(farmEquipmentBrands);
        }
        vehicleBrandSection.style.display = 'block';
    });

    petTypeSelect.addEventListener('change', function () {
        var selectedPetType = petTypeSelect.value;
        
        if (selectedPetType === 'Dog') {
            var dogBrands = [
                'Buldogs', 'Labradors', 'Retrīvers', 'Šnaucērs', 'Vjekšnis', 'Bigls', 'Rottvailers', 
                'Nūfāundlenders', 'Šarpejs', 'Pūdels', 'Dalmatiņš', 'Bāzēnu džeks', 'Kavalerkinga španiēls', 
                'Velsas korgis', 'Austrālijas pārstāvnieks', 'Bernu kalns', 'Šiba inu', 'Sanktbernards', 'Dobermans', 
                'Samojeds', 'Airedeli terjers', 'Kairni terjers', 'Alabai', 'Mopss', 'Akita', 'Cits'
            ];
            populatePetBrands(dogBrands);
        } else if (selectedPetType === 'Cat') {
            var catBrands = [
                'Persietis', 'Maine Coons', 'Siamieši', 'Ragdolls', 'Bengāļi', 'Sfinksi', 'Skotu šķirnes',
                'Britu īsspalvainie', 'Abyssinians', 'Birma', 'Birmans', 'Krievu zilie', 'Norvēģu meža kaķi', 'Sibīrieši', 'Orientāļu īsspalvainie',
                'Kornvolas reksi', 'Devon reksi', 'Menks', 'Himalaji', 'Turku angore', 'Turku vans', 'Balinezijs', 'Javāņi', 'Cits'
            ];
            populatePetBrands(catBrands);
        } else if (selectedPetType === 'Rats') {
            var ratBrands = [
                'Degu', 'Fretkas', 'Seski', 'Jūrascūciņas', 'Kāmji', 'Mājas žurkas', 'Šinšilas',
                'Truši', 'Cits'
            ];
            populatePetBrands(ratBrands);
        } else if (selectedPetType === 'Fish') {
            var fishBrands = [
                'Zivtiņas', 'Akvāriji', 'Barība', 'Ūdens augi', 'Cits'
            ];
            populatePetBrands(fishBrands);
        } else if (selectedPetType === 'Birds') {
            var birdBrands = [
                'Kanārijputniņi', 'Papagaiļi', 'Barība', 'Būri', 'Cits'
            ];
            populatePetBrands(birdBrands);
        } else if (selectedPetType === 'BigAnimals') {
            var bigBrands = [
                'Auni, Aitas', 'Kazas', 'Cūkas', 'Liellopi', 'Zirgi, ēzeļi', 'Zaķi, Nūtrijas', 'Cits'
            ];
            populatePetBrands(bigBrands);
        }
        petBrandSection.style.display = 'block';
    });

    electronicTypeSelect.addEventListener('change', function () {
        var selectedElectronicType = electronicTypeSelect.value;

        if (selectedElectronicType === 'Contact') {
            var contactBrands = [
                'Samsung', 'Apple', 'Huawei', 'Xiaomi', 'OnePlus', 'Google Pixel', 'Sony', 'LG', 'Motorola', 'Nokia', 'OPPO', 'Vivo', 'Realme', 'Asus', 'BlackBerry', 'HTC', 'Lenovo', 'ZTE', 'Alcatel', 'Cits'
            ];
            populateElectronicBrands(contactBrands);
        } else if (selectedElectronicType === 'Life') {
            var lifeTypes = [
                'Krāsns', 'Matu žāvētāji', 'Putekļsūcēji', 'Blenderi', 'Tējkannas', 'Kafijas automāti', 'Mikroviļņu krāsns', 'Gludeklis', 'Elektriskās tējkannas', 'Pārtikas apstrādes iekārtas', 'Ventilatori', 'Sildītāji', 'Gaisa attīrītāji', 'Cits'
            ];
            populateElectronicBrands(lifeTypes);
        } else if (selectedElectronicType === 'Computer') {
            var computerTypes = [
                'Stacionārais dators', 'Klēpjdators', 'Planšetdators', 'Mini dators', 'Gaming dators', 'Serveris', 'Superdators', 'Cits'
            ];
            populateElectronicBrands(computerTypes);
        } else if (selectedElectronicType === 'Audio') {
            var audioTypes = [
                'Austiņas', 'Skaļruņi', 'Pastiprinātāji', 'Bezvadu austiņas', 'Bluetooth skaļruņi', 'Hi-Fi sistēmas', 'Mikrofoni', 'Stereosistēmas', 'Aparatūra koncertiem', 'Cits'
            ];
            populateElectronicBrands(audioTypes);
        } else if (selectedElectronicType === 'Video') {
            var videoTypes = [
                'Projektori', 'Blu-ray atskaņotāji', 'Video kameru komplekti', 'Video ierakstītāji', 'LED ekrāni', 'Videokameras', 'Videokameru aksesuāri', 'Kino sistēmas', 'Cits'
            ];
            populateElectronicBrands(videoTypes);
        } else if (selectedElectronicType === 'TV') {
            var tvBrands = [
                'Samsung', 'LG', 'Sony', 'Philips', 'Panasonic', 'Toshiba', 'Sharp', 'Hisense', 'TCL', 'JVC', 'Grundig', 'Metz', 'Bang & Olufsen', 'Loewe', 'Pioneer', 'Cits'
            ];
            populateElectronicBrands(tvBrands);
        }
        electronicBrandSection.style.display = 'block';
    });

    jobTypeSelect.addEventListener('change', function () {
        var selectedJobType = jobTypeSelect.value;

        if (selectedJobType === 'Vacancy') {
            var vacancyBrands = ['Programmētājs', 'Dizainers', 'Menedžeris', 'Grāmatvedis', 'Mākslinieks', 'Projektu vadītājs', 'Jurists', 'Ārsts', 'Skolotājs', 'Inženieris', 'Zinātnieks', 'Reklāmas aģents', 'Pārdevējs', 'Kopējs', 'Automehāniķis', 'Kokapstrādes darbinieks',
                'Elektriķis', 'Pavārs', 'Frizieris', 'Autors', 'Ēku uzturēšanas darbinieks', 'Mājsaimniece', 'Tulkotājs', 'Cits'
            ];
            populateJobBrands(vacancyBrands);
        } else if (selectedJobType === 'Courses') {
            var CourseTypes = [
                'Programmēšanas kursi', 'Dizaina kursi', 'Valodu apguves kursi', 'Biznesa kursi', 'Finanšu kursi', 'Zinātnes kursi', 'Mākslas kursi', 'Fotogrāfijas kursi', 'Kulinārijas kursi', 'Sports un veselība', 'Personības attīstība', 'Mūzikas kursi', 'Datorspēļu attīstība',
                'Tehniskie kursi', 'Profesionālās apmācības', 'Radošums un rokdarbi', 'Zinātne un tehnoloģijas', 'Vides un dārzkopības kursi', 'Cits'
            ];            
            populateJobBrands(CourseTypes);
        } else if (selectedJobType === 'Job') {
            var jobTypes = [
                'Pilna laika', 'Puslaika', 'Darbs no mājām', 'Mājas saimnieks/-niece', 'Ārzemēs', 'Darbs uz noteiktu laiku', 'Brīvprātīgais darbs', 'Cits'
            ];            
            populateJobBrands(jobTypes);
        }
        jobBrandSection.style.display = 'block';
    });

    furnitureTypeSelect.addEventListener('change', function () {
        var selectedFurnitureType = furnitureTypeSelect.value;

        if (selectedFurnitureType === 'Furniture') {
            var FurnitureTypes = [
                'Galdi', 'Krēsli', 'Sistemas', 'Gultas', 'Skapji', 'Gultekas', 'Stali', 'Mēbeļu komplekti', 'Biroja mēbeles', 'Dīvāni', 'Plaukti', 'Virtuves mēbeles', 'Vannasistabas mēbeles', 'Audums un ādas',
                'Dārza mēbeles', 'Bērnu mēbeles', 'Cits'];
            populateFurnitureBrands(FurnitureTypes);
        } else if (selectedFurnitureType === 'Art') {
            var ArtTypes = [
                'Akrils', 'Eļļa uz audekla', 'Akvareļi', 'Zīmulis un zīmulis', 'Kombinētie mediji', 'Pasteļi', 'Zīmējumi', 'Tekstūra un reliēfs', 'Fotorealisms',
                'Abstrakcija', 'Impresionisms', 'Ekspresionisms', 'Futūrisms', 'Pop māksla', 'Kubisms', 'Surreālisms', 'Modernisms', 'Minimalisms', 'Grafika un gravīra', 'Digitālā māksla', 'Cits'
            ];
            
            populateFurnitureBrands(ArtTypes);
        } else if (selectedFurnitureType === 'Plants') {
            var PlantTypes = [
                'Majas augi', 'Dārza augi', 'Ziedi', 'Zāles un ēdama raža', 'Majas kaktusi', 'Puķes un puķu dārzs', 'Dārza koki un krūmi',
                'Dārza dizains un kopšana', 'Hidroponika un aeroponika', 'Dārza instrumenti un aprīkojums', 'Cits'
            ];            
            populateFurnitureBrands(PlantTypes);
        }
        furnitureBrandSection.style.display = 'block';
    });

    function populateVehicleBrands(brands) {
        vehicleBrandSelect.innerHTML = '';

        brands.forEach(function (brand) {
            var option = document.createElement('option');
            option.value = brand;
            option.textContent = brand;
            vehicleBrandSelect.appendChild(option);
        });
    }

    function populatePetBrands(brands) {
        petBrandSelect.innerHTML = '';

        brands.forEach(function (brand) {
            var option = document.createElement('option');
            option.value = brand;
            option.textContent = brand;
            petBrandSelect.appendChild(option);
        });
    }

    function populateJobBrands(brands) {
        jobBrandSelect.innerHTML = '';

        brands.forEach(function (brand) {
            var option = document.createElement('option');
            option.value = brand;
            option.textContent = brand;
            jobBrandSelect.appendChild(option);
        });
    }

    function populateFurnitureBrands(brands) {
        furnitureBrandSelect.innerHTML = '';

        brands.forEach(function (brand) {
            var option = document.createElement('option');
            option.value = brand;
            option.textContent = brand;
            furnitureBrandSelect.appendChild(option);
        });
    }

    function populateElectronicBrands(brands) {
        electronicBrandSelect.innerHTML = '';

        brands.forEach(function (brand) {
            var option = document.createElement('option');
            option.value = brand;
            option.textContent = brand;
            electronicBrandSelect.appendChild(option);
        });
    }
});
