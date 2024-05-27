<?php
require "db_con.php";
include("authorization.php");

function addWatermark($sourceImage, $watermarkImage, $destinationImage) {
    $source = imagecreatefromjpeg($sourceImage);
    
    $watermark = imagecreatefrompng($watermarkImage);
    
    $sourceWidth = imagesx($source);
    $sourceHeight = imagesy($source);
    $watermarkWidth = imagesx($watermark);
    $watermarkHeight = imagesy($watermark);
    
    $padding = 10;
    $watermarkX = $sourceWidth - $watermarkWidth - $padding;
    $watermarkY = $sourceHeight - $watermarkHeight - $padding;
    
    imagecopy($source, $watermark, $watermarkX, $watermarkY, 0, 0, $watermarkWidth, $watermarkHeight);
    
    imagejpeg($source, $destinationImage);
    
    imagedestroy($source);
    imagedestroy($watermark);
}

if (!isset($pdo)) {
    die("Connection not established. Check your database connection.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $pdo->beginTransaction();
        $latvianTranslations = [
            "Vehicle" => "Transports",
            "Pets" => "Dzīvnieki",
            "Electronic" => "Elektronika",
            "Job" => "Darbs un bizness",
            "Furniture" => "Mājai, dārzam",
            "Other" => "Cits",
            "Cars" => "Vieglā automašīna",
            "Heavy" => "Smagā automašīna",
            "Motorcycles" => "Motocikls",
            "WaterTransport" => "Ūdens transportlīdzeklis",
            "FarmEquipment" => "Lauksaimniecības tehnika",
            "Dog" => "Suns",
            "Cat" => "Kaķis",
            "Rats" => "Grauzējs",
            "Fish" => "Zivs",
            "Birds" => "Putns",
            "BigAnimals" => "Lauksaimniecības dzīvnieks",
            "Contact" => "Sakaru līdzeklis",
            "Life" => "Sadzīves tehnika",
            "Computer" => "Dators",
            "Audio" => "Audio tehnika",
            "Video" => "Video tehnika",
            "TV" => "Televizors",
            "Vacancy" => "Vakance",
            "Courses" => "Kursi",
            "Art" => "Gleznas",
            "Plants" => "Augi",
        ];

        $latvianAdType = $latvianTranslations[$_POST["adType"]];

        $queryAds = "INSERT INTO ads (adName, adPrice, adDescription, adLocation, adType, sellerId) VALUES (:adName, :adPrice, :adDescription, :adLocation, :adType, :sellerId)";
        $stmtAds = $pdo->prepare($queryAds);
        $stmtAds->bindParam(':adName', $_POST["adName"]);
        $stmtAds->bindParam(':adPrice', $_POST["adPrice"]);
        $stmtAds->bindParam(':adDescription', $_POST["adDescription"]);
        $stmtAds->bindParam(':adLocation', $_POST["adLocation"]);
        $stmtAds->bindParam(':adType', $latvianAdType);
        $stmtAds->bindParam(':sellerId', $_SESSION["id"]);
        $stmtAds->execute();

        $adId = $pdo->lastInsertId();

        switch ($_POST["adType"]) {
            case "Transports":
                $vehicleType = isset($_POST["vehicleType"]) ? $_POST["vehicleType"] : null;
                $vehicleBrand = isset($_POST["vehicleBrand"]) ? $_POST["vehicleBrand"] : null;

                $translatedVehicleType = isset($latvianTranslations[$vehicleType]) ? $latvianTranslations[$vehicleType] : $vehicleType;
                $translatedVehicleBrand = isset($latvianTranslations[$vehicleBrand]) ? $latvianTranslations[$vehicleBrand] : $vehicleBrand;

                $queryVehicle = "INSERT INTO vehicles (adId, vehicleType, vehicleBrand) VALUES (?, ?, ?)";
                $stmtVehicle = $pdo->prepare($queryVehicle);
                $stmtVehicle->bindParam(1, $adId);
                $stmtVehicle->bindParam(2, $translatedVehicleType);
                $stmtVehicle->bindParam(3, $translatedVehicleBrand);
                $stmtVehicle->execute();
                break;

            case "Dzīveniki":
                $petType = isset($_POST["petType"]) ? $_POST["petType"] : null;
                $petBrand = isset($_POST["petBrand"]) ? $_POST["petBrand"] : null;

                $translatedpetType = isset($latvianTranslations[$petType]) ? $latvianTranslations[$petType] : $petType;
                $translatedpetBrand= isset($latvianTranslations[$petBrand]) ? $latvianTranslations[$petBrand] : $petBrand;

                $queryPet = "INSERT INTO pets (adId, petType, petBrand) VALUES (?, ?, ?)";
                $stmtPet = $pdo->prepare($queryPet);
                $stmtPet->bindParam(1, $adId);
                $stmtPet->bindParam(2, $translatedpetType);
                $stmtPet->bindParam(3, $translatedpetBrand);
                $stmtPet->execute();
                break;

            case "Elektronika":
                $electronicType = isset($_POST["electronicType"]) ? $_POST["electronicType"] : null;
                $electronicBrand = isset($_POST["electronicBrand"]) ? $_POST["electronicBrand"] : null;

                $translatedelectronicType = isset($latvianTranslations[$electronicType]) ? $latvianTranslations[$electronicType] : $electronicType;
                $translatedelectronicBrand= isset($latvianTranslations[$electronicBrand]) ? $latvianTranslations[$electronicBrand] : $electronicBrand;

                $queryElectronic = "INSERT INTO electronics (adId, electronicType, electronicBrand) VALUES (?, ?, ?)";
                $stmtElectronic = $pdo->prepare($queryElectronic);
                $stmtElectronic->bindParam(1, $adId);
                $stmtElectronic->bindParam(2, $translatedelectronicType);
                $stmtElectronic->bindParam(3, $translatedelectronicBrand);
                $stmtElectronic->execute();
                break;

            case "Darbs un bizness":
                $jobType = isset($_POST["JobType"]) ? $_POST["JobType"] : null;
                $jobBrand = isset($_POST["JobBrand"]) ? $_POST["JobBrand"] : null;

                $translatedejobType = isset($latvianTranslations[$jobType]) ? $latvianTranslations[$jobType] : $jobType;
                $translatedejobBrand= isset($latvianTranslations[$jobBrand]) ? $latvianTranslations[$jobBrand] : $jobBrand;

                $queryJob = "INSERT INTO jobs (adId, jobType, jobBrand) VALUES (?, ?, ?)";
                $stmtJob = $pdo->prepare($queryJob);
                $stmtJob->bindParam(1, $adId);
                $stmtJob->bindParam(2, $translatedejobType);
                $stmtJob->bindParam(3, $translatedejobBrand);
                $stmtJob->execute();
                break;

            case "Mēbeles":
                $furnitureType = isset($_POST["FurnitureType"]) ? $_POST["FurnitureType"] : null;
                $furnitureBrand = isset($_POST["FurnitureBrand"]) ? $_POST["FurnitureBrand"] : null;

                $translatedefurnitureType = isset($latvianTranslations[$furnitureType]) ? $latvianTranslations[$furnitureType] : $furnitureType;
                $translatedefurnitureBrand= isset($latvianTranslations[$furnitureBrand]) ? $latvianTranslations[$furnitureBrand] : $furnitureBrand;

                $queryFurniture = "INSERT INTO furniture (adId, furnitureType, furnitureBrand) VALUES (?, ?, ?)";
                $stmtFurniture = $pdo->prepare($queryFurniture);
                $stmtFurniture->bindParam(1, $adId);
                $stmtFurniture->bindParam(2, $translatedefurnitureType);
                $stmtFurniture->bindParam(3, $translatedefurnitureBrand);
                $stmtFurniture->execute();
                break;

            case "Cits":
                $othersBrand = isset($_POST["othersBrand"]) ? $_POST["othersBrand"] : null;

                $queryOther = "INSERT INTO others (adId, othersBrand) VALUES (?, ?)";
                $stmtOther = $pdo->prepare($queryOther);
                $stmtOther->bindParam(1, $adId);
                $stmtOther->bindParam(2, $othersBrand);
                $stmtOther->execute();
                break;

            default:
                break;
        }

        if (!empty($_FILES["adImages"]["name"][0])) {
            $imageUploadPath = "C:\\xampp\htdocs\AdSpot\AdImages";
            
            $watermarkImage = "C:\\xampp\htdocs\AdSpot\LogoMark.png";
            
            foreach ($_FILES["adImages"]["name"] as $key => $value) {
                $imageName = time() . '_' . $key . '_' . $_FILES["adImages"]["name"][$key];
                move_uploaded_file($_FILES["adImages"]["tmp_name"][$key], $imageUploadPath . DIRECTORY_SEPARATOR . $imageName);
                
                addWatermark($imageUploadPath . DIRECTORY_SEPARATOR . $imageName, $watermarkImage, $imageUploadPath . DIRECTORY_SEPARATOR . $imageName);
                
                $imageQuery = "INSERT INTO ad_images (ad_id, image_path) VALUES (:adId, :imageName)";
                $imageStmt = $pdo->prepare($imageQuery);
                $imageStmt->bindParam(':adId', $adId);
                $imageStmt->bindParam(':imageName', $imageName);
                $imageStmt->execute();
            }
        }

        $pdo->commit();
        header("Location: \AdSpot\dashboard.php");
        exit();
    } catch (Exception $e) {
        $pdo->rollBack();
        echo "Error: " . $e->getMessage();
    }
} else {
    header("Location: index.php");
    exit();
}
?>
