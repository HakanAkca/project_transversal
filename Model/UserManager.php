<?php

namespace Model;


class UserManager
{
    private $DBManager;
    private static $instance = null;

    public static function getInstance()
    {
        if (self::$instance === null)
            self::$instance = new UserManager();
        return self::$instance;
    }

    private function __construct()
    {
        $this->DBManager = DBManager::getInstance();
    }

    public function getUserById($id)
    {
        $id = (int)$id;
        $data = $this->DBManager->findOne("SELECT * FROM users WHERE id = " . $id);
        return $data;
    }

    public function getUserByUsername($username)
    {
        $data = $this->DBManager->findOneSecure("SELECT * FROM users WHERE pseudo = :username",
            ['username' => $username]);
        return $data;
    }

    public function allUsers()
    {
        return $this->DBManager->findAllSecure("SELECT * FROM users");
    }

    public function getPartnerByName($name)
    {
        $data = $this->DBManager->findOneSecure("SELECT * FROM partners WHERE name = :name",
            ['name' => $name]);
        return $data;
    }

    public function getBarcodeByBarcode($barcode)
    {
        $data = $this->DBManager->findOneSecure("SELECT * FROM barcodes WHERE barcode = :barcode",
            [
                'barcode' => $barcode,
            ]);
        return $data;
    }

    public function getDealById($d)
    {
        $id = (int)$d;
        $data = $this->DBManager->findOneSecure("SELECT * FROM catalogs WHERE id = :id",
            [
                'id' => $id,
            ]);
        return $data;
    }

    public function printDeal($d)
    {
        $id = (int)$d;
        $data = $this->DBManager->findOneSecure("SELECT * FROM catalogs WHERE id = :id",
            [
                'id' => $id,
            ]);
        $barcode = $_SESSION['user_id'] . $this->generateBarcode();

    }

    public function checkProfile($data)
    {
        $isFormGood = true;
        $errors = array();
        $res = array();
        if (isset($_FILES['userfile']['name']) && !empty($_FILES)) {
            $data['userfile'] = $_FILES['userfile']['name'];
            $data['file_tmp_name'] = $_FILES['userfile']['tmp_name'];
            $res['data'] = $data;
        } else {
            $errors['userfile'] = 'Veillez choisir une image';
            $isFormGood = false;
        }
        if (!isset($data['editUsername']) || !$this->usernameValid($data['editUsername'])) {
            $errors['username'] = 'Veuillez saisir un pseudo de 6 caractères minimum';
            $isFormGood = false;
        }
        if ($data['editUsername'] !== $_SESSION['user_username']) {
            $data2 = $this->getUserByUsername($data['editUsername']);
            if ($data2 !== false) {
                $errors['username'] = 'Le pseudo existe déjà';
                $isFormGood = false;
            }
        }
        if (!isset($data['editMail']) || !$this->emailValid($data['editMail'])) {
            $errors['username'] = 'Adresse email non valide';
            $isFormGood = false;
        }
        if (!isset($data['editCity']) || empty($data['editCity'])) {
            $errors['username'] = 'Adresse email non valide';
            $isFormGood = false;
        }
        $res['errors'] = $errors;
        $res['isFormGood'] = $isFormGood;
        return $res;
    }

    public function editProfile($data)
    {
        $pseudo = $data['editUsername'];
        $email = $data['editMail'];
        $city = $data['editCity'];
        $file = '';
        $file_tmp_name = '';
        $id = $_SESSION['user_id'];
        $username = $_SESSION['user_username'];

        if (!empty($data['userfile']) && !empty($data['file_tmp_name'])) {
            $file = $data['userfile'];
            $file_tmp_name = $data['file_tmp_name'];
        }
        if (!empty($file) && !empty($file_tmp_name)) {
            $new_file_url = 'uploads/' . $pseudo . '/' . $file;
            rename('uploads/' . $username, 'uploads/' . $pseudo);
            move_uploaded_file($file_tmp_name, $new_file_url);
            return $this->DBManager->findOneSecure(
                "UPDATE users SET pseudo =:pseudo, email =:email, city =:city, image =:new_file_url  WHERE id=:id",
                [
                    'pseudo' => $pseudo,
                    'email' => $email,
                    'city' => $city,
                    'new_file_url' => $new_file_url,
                    'id' => $id,
                ]);
        } else {
            rename('uploads/' . $username, 'uploads/' . $pseudo);
            return $this->DBManager->findOneSecure(
                "UPDATE users SET pseudo =:pseudo, email =:email, city =:city WHERE id=:id",
                [
                    'pseudo' => $pseudo,
                    'email' => $email,
                    'city' => $city,
                    'id' => $id,
                ]);
        }
    }


    public function userCheckRegister($data)
    {
        $errors = array();
        $res = array();
        $isFormGood = true;

        $data['image'] = 'web/img/avatar.gif';
        if (!isset($data['username']) || !$this->usernameValid($data['username'])) {
            $errors['username'] = 'Pseudo de 6 caractères minimum';
            $isFormGood = false;
        }
        $data2 = $this->getUserByUsername($data['username']);
        if ($data2 !== false) {
            $errors['username'] = 'Le pseudo existe déjà';
            $isFormGood = false;
        }

        if (!$this->emailValid($data['email'])) {
            $errors['email'] = "email non valide";
            $isFormGood = false;
        }

        if (!isset($data['password']) || !$this->passwordValid($data['password'])) {
            $errors['password'] = "Veiller saisir un mot de passe valide ";
            $isFormGood = false;
        }
        /*if($this->passwordValid($data['password']) && $data['password'] !== $data['verifpassword']){
            $errors['password'] = "Les deux mot de passe ne sont pas identiques";
            $isFormGood = false;
        }*/


        if (!isset($data['city']) || !$this->cityValid($data['city'])) {
            $errors['city'] = 'Merci de saissir une ville';
            $isFormGood = false;
        }

        $res['isFormGood'] = $isFormGood;
        $res['errors'] = $errors;
        $res['data'] = $data;
        return $res;
    }


    private function emailValid($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    private function usernameValid($username)
    {
        return preg_match('`^([a-zA-Z0-9-_]{6,20})$`', $username);
    }

    private function cityValid($city)
    {
        return preg_match('`^([a-zA-Z0-9-_]{1,15})$`', $city);
    }

    //Minimum : 8 caractères avec au moins une lettre majuscule et un nombre
    private function passwordValid($password)
    {
        return preg_match('`^([a-zA-Z0-9-_]{8,20})$`', $password);
    }

    public function phoneNumberValid($phoneNumber, $international = false)
    {
        $phoneNumber = preg_replace('/[^0-9]+/', '', $phoneNumber);
        $phoneNumber = substr($phoneNumber, -9);
        $motif = $international ? '+33 (\1) \2 \3 \4 \5' : '0\1 \2 \3 \4 \5';
        $phoneNumber = preg_replace('/(\d{1})(\d{2})(\d{2})(\d{2})(\d{2})/', $motif, $phoneNumber);

        return $phoneNumber;
    }

    private function userHash($pass)
    {
        $hash = password_hash($pass, PASSWORD_BCRYPT);
        return $hash;
    }

    public function userRegister($data)
    {
        $user['pseudo'] = $data['username'];
        $user['email'] = $data['email'];
        $user['city'] = ucwords($data['city']);
        $user['password'] = $this->userHash($data['password']);
        $user['costs'] = 0;
        $user['bottlesNumber'] = 0;
        $user['level'] = 1;
        $user['date'] = $this->getDatetimeNow();
        $user['image'] = $data['image'];
        $user['vote'] = 0;

        $this->DBManager->insert('users', $user);
        mkdir("uploads/" . $user['pseudo']);
    }

    public function userCheckLogin($data)
    {
        $isFormGood = true;
        $errors = array();
        $user = $this->getUserByUsername($data['username']);
        if (empty($data['username']) OR empty($data['password'])) {
            $errors['Connexion field'] = 'Missing fields';
            $isFormGood = false;
        }
        if (!password_verify($data['password'], $user['password'])) {
            $errors['matchingPassword'] = 'Login ou mdp incorrect';
            $isFormGood = false;
        }
        return $isFormGood;
    }


    public function userLogin($username)
    {
        $data = $this->getUserByUsername($username);
        if ($data === false)
            return false;
        $_SESSION['user_id'] = $data['id'];
        $_SESSION['user_username'] = $data['pseudo'];
        $date = $this->DBManager->take_date();
        $write = $date . ' -- ' . $_SESSION['user_username'] . ' is connected' . "\n";
        $this->DBManager->watch_action_log('access.log', $write);
        return true;
    }

    public function checkPartner($data)
    {
        $errors = array();
        $res = array();
        $isFormGood = true;
        if (!isset($data['name']) || empty($data['name'])) {
            $errors['name'] = 'Veuillez saisir le nom du partenaire';
            $isFormGood = false;
        }
        $data2 = $this->getPartnerByName($data['name']);
        if ($data2 !== false) {
            $errors['name'] = 'Le partenaire existe déjà';
            $isFormGood = false;
        }
        if (!$this->emailValid($data['email'])) {
            $errors['email'] = "email non valide";
            $isFormGood = false;
        }
        if (!isset($data['city']) || empty($data['city'])) {
            $errors['city'] = 'Veuillez saisir une ville valide';
            $isFormGood = false;
        }
        if (!isset($data['phone']) || !$this->phoneNumberValid($data['phone'])) {
            $errors['phone'] = 'Veuillez saisir un numéro de téléphone valide';
            $isFormGood = false;
        }
        if (!isset($data['status']) || empty($data['status'])) {
            $errors['status'] = 'Veuillez saisir une status valide';
            $isFormGood = false;
        }

        $res['isFormGood'] = $isFormGood;
        $res['errors'] = $errors;
        $res['data'] = $data;
        return $res;
    }

    public function bePartner($data)
    {
        $partner['name'] = $data['name'];
        $partner['email'] = $data['email'];
        $partner['city'] = ucwords($data['city']);
        $partner['phone'] = $data['phone'];
        $partner['status'] = $data['status'];

        $this->DBManager->insert('partners', $partner);
    }

    public function checkRemoveAccount($data)
    {
        $isFormGood = true;
        $errors = array();
        if (empty($data['pseudo'])) {
            $errors['user'] = 'Le champs pseudo est vide';
            $isFormGood = false;
        }

        $user = $this->getUserByUsername($data['pseudo']);
        if ($user === false) {
            $errors['user'] = 'Le pseudo existe déjà';
            $isFormGood = false;
        }
        if ($isFormGood) {
            echo(json_encode(array('success' => true, 'user' => $_POST)));
        } else {
            http_response_code(400);
            echo(json_encode(array('success' => false, 'errors' => $errors)));
            exit(0);
        }
        return $isFormGood;
    }

    public function deleteAccount($data)
    {
        $pseudo = $data['pseudo'];
        $date = $this->DBManager->take_date();
        $write = $date . ' -- ' . $_SESSION['user_username'] . ' à supprimer le compte suivant ' . $data['pseudo'] . "\n";
        $this->DBManager->watch_action_log('admin.log', $write);
        return $this->DBManager->findOneSecure("DELETE FROM users WHERE pseudo =:pseudo",
            ['pseudo' => $pseudo]);


    }

    public function checkCatalog($data)
    {
        $isFormGood = true;
        $errors = array();
        $res = array();
        if (isset($_FILES['image']['name']) && !empty($_FILES)) {
            $data['image'] = $_FILES['image']['name'];
            $data['image_tmp_name'] = $_FILES['image']['tmp_name'];
            $res['data'] = $data;
        } else {
            $errors['image'] = 'Veillez choisir une image';
            $isFormGood = false;
        }

        $data2 = $this->getDealByTitle($data['partner']);
        if ($data2 !== false) {
            $errors['partner'] = "Veillez un autre titre";
            $isFormGood = false;
        }
        if (!isset($data['partner']) | empty($data['partner'])) {
            $errors['partner'] = "Veillez choisir un partenaire";
            $isFormGood = false;
        }
        if (!isset($data['city']) | empty($data['city'])) {
            $errors['city'] = "Veillez choisir une ville";
            $isFormGood = false;
        }
        if (!isset($data['deal']) | empty($data['deal'])) {
            $errors['deal'] = "Veillez choisir une offre";
            $isFormGood = false;
        }
        if (!isset($data['cost']) | empty($data['cost'])) {
            $errors['cost'] = "Veillez choisir un cost";
            $isFormGood = false;
        }
        if (!isset($data['description']) | empty($data['description'])) {
            $isFormGood = false;
            $errors['description'] = "Veillez remplir le champ description";
        }
        if (!isset($data['expirationDate']) | empty($data['expirationDate'])) {
            $errors['expirationDate'] = "Veillez mettre une date d'expiration valide";
            $isFormGood = false;
        }
        if (isset($data['expirationDate']) && !empty($data['expirationDate'])) {
            if (!$this->checkExpirationDate($data['expirationDate'])) {
                $errors['expirationDate'] = "Veillez mettre une date d'expiration valide";
                $isFormGood = false;
            } else {
                $strToDate = strtotime($data['expirationDate']);
                $expirationDate = date('Y/m/d H:i:s', $strToDate);
                $data['expirationDate'] = $expirationDate;
            }
        }

        $res['isFormGood'] = $isFormGood;
        $res['errors'] = $errors;
        return $res;
    }

    public function addCatalog($data)
    {
        $filetmpname = $data['image_tmp_name'];
        $url = 'uploads/'.$data['partner'] . '.png';
        $catalog['partner'] = $data['partner'];
        $catalog['city'] = ucwords($data['city']);
        $catalog['deal'] = $data['deal'];
        $catalog['cost'] = $data['cost'];
        $catalog['description'] = $data['description'];
        $catalog['image'] = $url;
        $catalog['date'] = $this->getDatetimeNow();
        $day = (int)substr($data['expirationDate'], 0, 2);
        $month = (int)substr($data['expirationDate'], 3, 2);
        $year = (int)substr($data['expirationDate'], 6, 4);
        $tmpDate = $month . '/' . $day . '/' . $year;
        $expirationDate = strtotime($tmpDate);
        $catalog['expirationDate'] = date('Y/m/d H:i:s', $expirationDate);
        $this->DBManager->insert('catalogs', $catalog);
        move_uploaded_file($filetmpname, $url);
        $date = $this->DBManager->take_date();
        $write = $date . ' -- ' . $_SESSION['user_username'] . ' à ajouter une nouvelle offre  '
            . ' nom : ' . $data['partner'] . ' ' . 'ville : ' . $data['city'] . ' ' . 'deal : ' . $data['deal'] . '  ' . 'cout : ' . $data['cost']
            . ' ' . 'description : ' . $data['description'] . ' ' . 'image : ' . $data['image'] . ' ' . 'date expiration : ' . $data['date'] . "\n";
        $this->DBManager->watch_action_log('admin.log', $write);
    }

    public function checkSurvey($data)
    {
        $isFormGood = true;
        $errors = array();
        $res = array();
        if (isset($_FILES['image']['name']) && !empty($_FILES)) {
            $data['image'] = $_FILES['image']['name'];
            $data['image_tmp_name'] = $_FILES['image']['tmp_name'];
            $res['data'] = $data;
        } else {
            $errors['image'] = 'Veillez choisir une image';
            $isFormGood = false;
        }

        if (!isset($data['partner']) | empty($data['partner'])) {
            $errors['partner'] = "Veillez choisir un partenaire";
            $isFormGood = false;
        }
        if (!isset($data['description']) | empty($data['description'])) {
            $isFormGood = false;
            $errors['description'] = "Veillez remplir le champ description";
        }
        if (!isset($data['deal']) | empty($data['deal'])) {
            $isFormGood = false;
            $errors['deal'] = "Veillez remplir le champ deal";
        }

        $res['isFormGood'] = $isFormGood;
        $res['errors'] = $errors;
        return $res;
    }

    public function countSurveyTmp()
    {
        return $this->DBManager->findAllSecure("SELECT COUNT(*) FROM surveys_tmp");
    }

    public function addSurveyTmp($data)
    {
        $filetmpname = $data['image_tmp_name'];
        $url = 'uploads/poll/' . $data['image'];
        $cur = strtotime($this->getDatetimeNow());
        $expirationDate = date('Y/m/d H:i:s', strtotime('+1 month', $cur));
        $survey['partner'] = $data['partner'];
        $survey['description'] = $data['description'];
        $survey['deal'] = $data['deal'];
        $survey['image'] = $url;
        $survey['expirationDate'] = $expirationDate;
        $survey['vote'] = 0;
        $this->DBManager->insert('surveys_tmp', $survey);
        move_uploaded_file($filetmpname, $url);
        $date = $this->DBManager->take_date();
        $write = $date . ' -- ' . $_SESSION['user_username'] . ' à ajouter une nouveau sondage  '
            . ' nom : ' . $data['partner'] . ' ' . ' description : ' . $data['description'] . ' ' . 'deal : ' . $data['deal']
            . ' ' . 'image : ' . $url . ' ' . 'date expiration : ' . $expirationDate . "\n";
        $this->DBManager->watch_action_log('admin.log', $write);
    }

    public function removeSurveyTmp()
    {
        return $this->DBManager->findOneSecure("DELETE FROM surveys_tmp");
    }

    public function surveyNumber()
    {
        return $this->DBManager->findAllSecure("SELECT * FROM surveys_tmp ORDER BY expirationDate DESC");
    }

    public function addSurvey($data)
    {
        $cur = strtotime($this->getDatetimeNow());
        $expirationDate = date('Y/m/d H:i:s', strtotime('+1 month', $cur));
        $survey['partner'] = $data['partner'];
        $survey['description'] = $data['description'];
        $survey['deal'] = $data['deal'];
        $survey['image'] = $data['image'];
        $survey['expirationDate'] = $expirationDate;
        $survey['vote'] = 1;
        $this->DBManager->insert('surveys', $survey);
    }

    public function getSurvey()
    {
        $cur = strtotime($this->getDatetimeNow());
        $currentDate = date('Y/m/d H:i:s', $cur);
        $res = array();
        $data = $this->DBManager->findAllSecure("SELECT * FROM surveys ORDER BY expirationDate DESC");

        foreach ($data as $value) {
            $exp = strtotime($value['expirationDate']);
            $expirationDate = date('Y/m/d H:i:s', $exp);

            $date1 = date_create($currentDate);
            $date2 = date_create($expirationDate);
            $diff = date_diff($date1, $date2);
            $interval = (int)$diff->format("%R%a");
            if ($interval >= 0) {
                $res[] = $value;
            }
        }
        return $res;
    }

    public function getSurveyTmp()
    {
        $cur = strtotime($this->getDatetimeNow());
        $currentDate = date('Y/m/d H:i:s', $cur);
        $res = array();
        $data = $this->DBManager->findAllSecure("SELECT * FROM surveys_tmp ORDER BY expirationDate DESC");

        foreach ($data as $value) {
            $exp = strtotime($value['expirationDate']);
            $expirationDate = date('Y/m/d H:i:s', $exp);

            $date1 = date_create($currentDate);
            $date2 = date_create($expirationDate);
            $diff = date_diff($date1, $date2);
            $interval = (int)$diff->format("%R%a");
            if ($interval >= 0) {
                $res[] = $value;
            }
        }
        return $res;
    }

    public function updateSurvey()
    {
        $data = $this->getSurvey();
        if (empty($data)) {
            $vote = 0;
            $this->DBManager->findAllSecure("UPDATE users SET vote = :vote",
                [
                    'vote' => $vote,
                ]);
        }
    }

    public function checkVote($id)
    {
        $vote = 0;
        return $this->DBManager->findOneSecure("SELECT * FROM users WHERE id =:id AND vote =:vote",
            [
                'vote' => $vote,
                'id' => $id
            ]);
    }

    public function userVote($data)
    {
        $user_id = $_SESSION['user_id'];
        $survey_id = (int)$data['surveyID'];
        $currentNumbersVotes = $this->getSurveysVotes($survey_id) + 1;
        $vote = 1;

        $this->setSurveysVotes($survey_id, $currentNumbersVotes);

        $this->DBManager->findOneSecure("UPDATE users SET vote = :vote WHERE id=:user_id",
            [
                'user_id' => $user_id,
                'vote' => $vote,
            ]);
    }

    public function getSurveysVotes($id)
    {
        $res = 0;
        $data = $this->DBManager->findAllSecure("SELECT vote FROM surveys WHERE  id =:id",
            [
                'id' => $id,
            ]);
        foreach ($data as $vote) {
            $res += (int)$vote['vote'];
        }
        return $res;
    }

    public function setSurveysVotes($id, $numbersVotes)
    {
        return $this->DBManager->findOneSecure("UPDATE surveys SET vote = :numbersVotes WHERE id=:id",
            [
                'id' => $id,
                'numbersVotes' => $numbersVotes,
            ]);
    }

    public function allVotes()
    {
        $res = 0;
        $data = $this->DBManager->findAllSecure("SELECT vote FROM surveys");
        foreach ($data as $vote) {
            $res += (int)$vote['vote'];
        }
        return $res;
    }

    public function getAllDeals()
    {
        $cur = strtotime($this->getDatetimeNow());
        $currentDate = date('Y/m/d H:i:s', $cur);
        $res = array();
        $data = $this->DBManager->findAllSecure("SELECT * FROM catalogs ORDER BY date DESC");

        foreach ($data as $value) {
            $exp = strtotime($value['expirationDate']);
            $expirationDate = date('Y/m/d H:i:s', $exp);

            $date1 = date_create($currentDate);
            $date2 = date_create($expirationDate);
            $diff = date_diff($date1, $date2);
            $interval = (int)$diff->format("%R%a");
            if ($interval >= 0) {
                $res[] = $value;
            }
        }
        return $res;
    }

    public function getDealsByCity($data)
    {
        $city = ucwords($data);
        $cur = strtotime($this->getDatetimeNow());
        $currentDate = date('Y/m/d H:i:s', $cur);
        $res = array();
        $data2 = $this->DBManager->findAllSecure("SELECT * FROM catalogs WHERE city =:city ORDER BY date DESC",
            ['city' => $city]);
        foreach ($data2 as $value) {
            $exp = strtotime($value['expirationDate']);
            $expirationDate = date('Y/m/d H:i:s', $exp);

            $date1 = date_create($currentDate);
            $date2 = date_create($expirationDate);
            $diff = date_diff($date1, $date2);
            $interval = (int)$diff->format("%R%a");
            if ($interval >= 0) {
                $res[] = $value;
            }
        }
        return $res;
    }

    public function getDealByTitle($t)
    {
        $partner = $t;
        return $this->DBManager->findOneSecure("SELECT * FROM catalogs WHERE partner =:partner", ['partner' => $partner]);
    }

    public function checkUpdateOffer($data)
    {
        $isFormGood = true;
        $errors = array();
        $res = array();
        if (isset($_FILES['fileOffer']['name']) && !empty($_FILES)) {
            $data['fileOffer'] = $_FILES['fileOffer']['name'];
            $data['fileOffer_tmp_name'] = $_FILES['fileOffer']['tmp_name'];
            $res['data'] = $data;
        } else {
            $errors['fileOffer'] = 'Veillez choisir une image';
            $isFormGood = false;
        }

        $data2 = $this->getDealByTitle($data['partner']);
        if ($data2 !== false && $data2['id'] !== $data['id']) {
            $errors['partner'] = "Veillez un autre titre";
            $isFormGood = false;
        }
        if (!isset($data['description']) | empty($data['description'])) {
            $isFormGood = false;
            $errors['description'] = "Veillez remplir le champ description";
        }
        $res['isFormGood'] = $isFormGood;
        $res['errors'] = $errors;

        return $res;
    }

    public function updateOffer($data)
    {
        $partner = $data['partner'];
        $description = $data['description'];
        $id = (int)$data['id'];
        if (!empty($file) && !empty($file_tmp_name)) {
            return $this->DBManager->findOneSecure("UPDATE catalogs SET partner = :partner, description =:description WHERE id=:id",
                [
                    'id' => $id,
                    'partner' => $partner,
                    'description' => $description,
                ]);
        } else {
            $image = 'uploads/' . $data['fileOffer'];
            $image_tmp_name = $data['fileOffer_tmp_name'];
            move_uploaded_file($image_tmp_name, $image);
            return $this->DBManager->findOneSecure("UPDATE catalogs SET partner = :partner, description =:description, image =:image WHERE id=:id",
                [
                    'id' => $id,
                    'partner' => $partner,
                    'description' => $description,
                    'image' => $image,
                ]);
        }

    }

    public function getAvailableDeals()
    {
        $cost = $this->getUserCostsNumber();
        $user = $this->getUserById($_SESSION['user_id']);
        $city = $user['city'];
        $cur = strtotime($this->getDatetimeNow());
        $currentDate = date('Y/m/d H:i:s', $cur);
        $res = array();
        $data = $this->DBManager->findAllSecure("SELECT * FROM catalogs WHERE cost <=:cost AND city =:city ORDER BY date DESC",
            [
                'cost' => $cost,
                'city' => $city,
            ]);
        foreach ($data as $value) {
            $exp = strtotime($value['expirationDate']);
            $expirationDate = date('Y/m/d H:i:s', $exp);

            $date1 = date_create($currentDate);
            $date2 = date_create($expirationDate);
            $diff = date_diff($date1, $date2);
            $interval = (int)$diff->format("%R%a");
            if ($interval >= 0) {
                $res[] = $value;
            }
        }
        return $res;
    }

    public function getUserDeals()
    {
        $res = array();
        $user_id = (string)$_SESSION['user_id'];
        $data = $this->DBManager->findAllSecure("SELECT * FROM deals WHERE user_id =:user_id ORDER BY date DESC",
            ['user_id' => $user_id]);
        foreach ($data as $catalog) {
            $res[] = $this->getDealById($catalog['catalog_id']);
        }
        return $res;
    }

    public function checkDump($data)
    {
        return is_numeric($data['bottlesNumber']);
    }

    public function addBarcode($data)
    {
        $barcode['barcode'] = $this->generateBarcode();
        $barcode['bottlesNumber'] = (int)$data['bottlesNumber'];
        $barcode['cost'] = $this->setCost((int)$data['bottlesNumber']);
        $barcode['barcodeUsed'] = 0;
        $this->DBManager->insert('barcodes', $barcode);

        $date = $this->DBManager->take_date();
        $write = $date . ' -- ' . $_SESSION['user_username'] . ' à ajouter des nouvelle bouteils ' . "\n";
        $this->DBManager->watch_action_log('admin.log', $write);
    }

    public function checkUserBarcode($data)
    {
        $isFormGood = true;
        if (empty($data['barcode'])) {
            $isFormGood = false;
        } else {
            $code = $this->getBarcodeByBarcode($data['barcode']);
            if ($code == false) {
                $isFormGood = false;
            }
            if ($code !== false && $code['barcodeUsed'] == 1) {
                $isFormGood = false;
            }
        }
        return $isFormGood;
    }

    public function barcodeUsed($data)
    {
        $barcode = $data;
        $barcodeUsed = 1;
        return $this->DBManager->findOneSecure("UPDATE barcodes SET barcodeUsed =:barcodeUsed WHERE barcode =:barcode",
            [
                'barcode' => $barcode,
                'barcodeUsed' => $barcodeUsed,
            ]);
    }

    public function chechBuyDeal($id)
    {
        $user_id = $_SESSION['user_id'];
        $deal = $this->getDealById($id);
        $user = $this->getUserById($user_id);
        $userCosts = (int)$user['costs'];
        $dealCosts = (int)$deal['cost'];
        return ($userCosts >= $dealCosts);
    }

    public function buyDeal($id)
    {
        $user_id = $_SESSION['user_id'];
        $deal = $this->getDealById($id);
        $userDeal['user_id'] = $user_id;
        $userDeal['catalog_id'] = (int)$deal['id'];
        //$userDeal['barcode'] =  $user_id.$this->generateBarcode();
        $userDeal['date'] = $this->getDatetimeNow();
        $this->setUserCostsNumber(-((int)$deal['cost']));
        $this->DBManager->insert('deals', $userDeal);
    }

    public function getAverages()
    {
        $users = $this->allUsers();
        $currentDate = date_create($this->getDatetimeNow());
        $average = array();
        foreach ($users as $user) {
            $id = $user['id'];
            $bottlesNumber = $user['bottlesNumber'];
            $registerDate = $user['date'];
            $strToDate = strtotime($registerDate);
            $registerDate = date('Y/m/d H:i:s', $strToDate);
            $date1 = date_create($registerDate);
            $diff = date_diff($date1, $currentDate);
            $interval = $diff->format("%R%d");
            $week = (float)$interval / 7;
            if ($week == 0) {
                $week = 1;
            }
            $average[$id] = round(($bottlesNumber) / $week);
        }
        return $average;
    }

    public function ranking()
    {
        $res = array();
        $averages = $this->getAverages();
        $r_averages = $averages;
        rsort($r_averages);
        $newAverage = array();
        foreach ($averages as $key => $value) {
            $newAverage[$value] = $key;
        }
        foreach ($r_averages as $key => $value) {
            $res[$newAverage[$value]] = $key + 1;
        }
        return $res;
    }


    public function checkExpirationDate($date)
    {
        $day = (int)substr($date, 0, 2);
        $month = (int)substr($date, 3, 2);
        $year = (int)substr($date, 6, 4);
        $bool = true;
        if (substr($date, 2, 1) != '/' || substr($date, 5, 1) != '/') {
            $bool = false;
        } else {
            if (checkdate($month, $day, $year)) {
                $currentDate = $this->getDatetimeNow();
                $date = $month . '/' . $day . '/' . $year;
                $strToDate = strtotime($date);
                $expirationDate = date('Y/m/d H:i:s', $strToDate);
                $date1 = date_create($currentDate);
                $date2 = date_create($expirationDate);
                $diff = date_diff($date1, $date2);
                $interval = (int)$diff->format("%R%a");
                if ($interval < 0) {
                    $bool = false;
                }
            } else {
                $bool = false;
            }
        }
        return $bool;
    }


    public function getUserBarcodes()
    {
        $user_id = $_SESSION['user_id'];
        $barcodeUsed = 0;
        return $this->DBManager->findAllSecure("SELECT * FROM barcodes WHERE user_id=:user_id AND barcodeUsed =:barcodeUsed",
            [
                'user_id' => $user_id,
                'barcodeUsed' => $barcodeUsed,
            ]);
    }

    public function updateLevel()
    {
        $user_id = $_SESSION['user_id'];
        $cost = $this->getUserCostsNumber();
        if ($cost < 10) {
            return;
        } elseif ($cost >= 10 && $cost < 30) {
            $level = 2;
            return $this->DBManager->findOneSecure("UPDATE users SET level = :level WHERE id=:user_id",
                [
                    'user_id' => $user_id,
                    'level' => $level,
                ]);
        } elseif ($cost >= 30 && $cost < 60) {
            $level = 3;
            return $this->DBManager->findOneSecure("UPDATE users SET level = :level WHERE id=:user_id",
                [
                    'user_id' => $user_id,
                    'level' => $level,
                ]);
        } elseif ($cost >= 60 && $cost < 100) {
            $level = 4;
            return $this->DBManager->findOneSecure("UPDATE users SET level = :level WHERE id=:user_id",
                [
                    'user_id' => $user_id,
                    'level' => $level,
                ]);
        } else {
            $level = 5;
            return $this->DBManager->findOneSecure("UPDATE users SET level = :level WHERE id=:user_id",
                [
                    'user_id' => $user_id,
                    'level' => $level,
                ]);
        }

    }

    public function getAllUsersBottlesRecycled()
    {
        $res = 0;
        $barcodeUsed = 1;
        $data = $this->DBManager->findAllSecure("SELECT bottlesNumber FROM barcodes WHERE barcodeUsed =:barcodeUsed",
            ['barcodeUsed' => $barcodeUsed]);
        foreach ($data as $bottles) {
            $res += (int)$bottles['bottlesNumber'];
        }
        return $res;
    }

    public function getUserBottlesRecycled($id)
    {
        $res = 0;
        $data = $this->DBManager->findAllSecure("SELECT bottlesNumber FROM users WHERE  id =:id",
            [
                'id' => $id,
            ]);
        foreach ($data as $bottles) {
            $res += (int)$bottles['bottlesNumber'];
        }
        return $res;
    }

    public function setUserBottlesRecycled($data)
    {
        $user_id = $_SESSION['user_id'];
        $bottlesNumber = (int)$data + $this->getUserBottlesRecycled($user_id);
        return $this->DBManager->findOneSecure("UPDATE users SET bottlesNumber = :bottlesNumber WHERE id=:user_id",
            [
                'user_id' => $user_id,
                'bottlesNumber' => $bottlesNumber,
            ]);
    }

    public function setUserCostsNumber($data)
    {
        $user_id = $_SESSION['user_id'];
        $costs = (int)($data + $this->getUserCostsNumber());
        return $this->DBManager->findOneSecure("UPDATE users SET costs = :costs WHERE id=:user_id",
            [
                'user_id' => $user_id,
                'costs' => $costs,
            ]);

    }

    public function getUserCostsNumber()
    {
        $user_id = $_SESSION['user_id'];
        $res = 0;
        $data = $this->DBManager->findAllSecure("SELECT costs FROM users WHERE id =:user_id",
            [
                'user_id' => $user_id,
            ]);
        foreach ($data as $cost) {
            $res += (int)$cost['costs'];
        }
        return $res;
    }

    public function setCost($data)
    {
        return $data * 2;
    }

    public function getDatetimeNow()
    {
        date_default_timezone_set('Europe/Paris');
        return date("Y/m/d H:i:s");
    }

    public function generateBarcode()
    {
        $characters = '0123456789';
        $barcode = '';
        for ($i = 0; $i < 6; $i++) {
            $barcode .= $characters[mt_rand(0, 9)];
        }
        return $barcode;
    }

    public function newsletterCheck($data)
    {
        $errors = array();
        $res = array();
        $isFormGood = true;

        if (!$this->emailValid($data)) {
            $errors['lol'] = "email non valide";
            $isFormGood = false;
        }

        $res['isFormGood'] = $isFormGood;
        $res['errors'] = $errors;
        $res['data'] = $data;
        return $res;
    }

    public function newslettersSend($d)
    {
        $res = array();
        $email = $d;
        $object = 'Inscription au newsletter';
        $content = '<html>
                <head>
                <title>Vous avez réservé sur notre site ...</title>
                </head>
                <body>
                <p>Vous êtes inscript au newsletter. Merci</p>
                </body>
                </html>';
        $res['email'] = $email;
        $res['object'] = $object;
        $res['content'] = $content;
        return $res;

    }

    public function checkRemoveOffers($data)
    {
        if (empty($data['offers']))
            return false;
        $offers = $this->DBManager->findAllSecure("SELECT * FROM catalogs");
        if ($offers === false)
            return false;
        return true;
    }

    public function removeOffer($data)
    {
        $partner = $data;
        $date = $this->DBManager->take_date();
        $write = $date . ' -- ' . $_SESSION['user_username'] . ' à supprimé l"offre suivante' . $data . "\n";
        $this->DBManager->watch_action_log('access.log', $write);
        return $this->DBManager->findOneSecure("DELETE FROM catalogs WHERE partner = :partner",
            ['partner' => $partner]);
    }

    public function addMail($data)
    {
        $user['email'] = $data['newsletter'];

        $this->DBManager->insert('newsletter', $user);
        $date = $this->DBManager->take_date();
        $write = $date . ' -- ' . ' Le mail suivant ' . $date['newsletter'] . ' viens de s"inscrire a la newsletter' . "\n";
        $this->DBManager->watch_action_log('access.log', $write);
    }

    public function getAllEmails()
    {
        return $this->DBManager->findAllSecure("SELECT email FROM newsletter");
    }

    public function checkSendNews($data)
    {
        $isFormGood = true;
        if (!isset($data['titreNewsletter']) | empty($data['titreNewsletter'])) {
            $isFormGood = false;
            $errors['titreNewsletter'] = "Veillez remplir le champ titreNewsletter";
        }
        if (!isset($data['newsletterContent']) | empty($data['newsletterContent'])) {
            $isFormGood = false;
            $errors['newsletterConten'] = "Veillez remplir le champ newsletterContent";
        }
        return $isFormGood;
    }

}
