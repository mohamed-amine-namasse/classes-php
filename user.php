<?php
declare(strict_types=1);

class User
{
    private int $id = 58;
    public string $login;
    public string $password;
    public string $email;
    public string $firstname;
    public string $lastname;

    private string $db_server = "localhost:3306";
    private string $db_user = "root";
    private string $db_password = "";
    private string $db_name = "classes";

    // Constructeur
    public function __construct(string $login , string $password , string $email , string $firstname , string $lastname )
    {
        $this->login = $login;
        $this->password = $password;
        $this->email = $email;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
    }

    // Méthode privée pour obtenir une connexion MySQLi
    private function getConnection(): mysqli
    {
        $conn = new mysqli($this->db_server, $this->db_user, $this->db_password, $this->db_name);
        if ($conn->connect_error) {
            die("Erreur de connexion : " . $conn->connect_error);
        }
        
        return $conn;
    }

    // Inscription d'un utilisateur
    public function register(string $login,string $password,string $email,string $firstname,string $lastname): array
    {
        $conn = $this->getConnection();
        
        // Insertion des données dans la BDD
        $stmt = $conn->prepare("INSERT INTO utilisateurs (login, password, email, firstname, lastname) VALUES (?, ?, ?, ?, ?)");
        if (!$stmt) {
            die("Erreur prepare : " . $conn->error);
        }
        
        
        $stmt->bind_param("sssss", $login, $password, $email, $firstname, $lastname);
        $success = $stmt->execute();
        $stmt->close();
        $conn->close();
        return [
            'login' => $this->login,
            'email' => $this->email,
            'firstname' => $this->firstname,
            'lastname' => $this->lastname
        ];

    }

    // Connexion / Authentification
    public function connect(string $login, string $password): bool
    {
        $conn = $this->getConnection();

        // Selectionne l'user et donne aux attributs de la classe les valeurs correspondantes
        $stmt = $conn->prepare("SELECT * FROM utilisateurs WHERE login = ? LIMIT 1");
        $stmt->bind_param("s", $login);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        if (!$user) {
            return false; // utilisateur non trouvé
        }
        
        
        // Authentification réussie → on remplit les attributs
        if ($password==$user['password']){
            $this->id = $user['id'];
            $this->login = $user['login'];
            $this->email = $user['email'];
            $this->firstname = $user['firstname'];
            $this->lastname = $user['lastname'];
            return true;
        }

        return false; // mauvais mot de passe
    }

    // Mise à jour des infos de l'utilisateur
    public function update(string $login, string $password, string $email, string $firstname, string $lastname): bool
    {
        $conn = $this->getConnection();

    
        if ($this->id === 0) {
        echo "Erreur : utilisateur non authentifié (id non défini)";
        return false;
    }
        $stmt = $conn->prepare("UPDATE utilisateurs SET login = ?, password = ?, email = ?, firstname = ?, lastname = ? WHERE id = ?");
        $stmt->bind_param("sssssi", $login, $password, $email, $firstname, $lastname, $this->id);
        $success = $stmt->execute();

        if ($success) {
            // Mise à jour locale
            $this->login = $login;
            $this->password = $password;
            $this->email = $email;
            $this->firstname = $firstname;
            $this->lastname = $lastname;
        } else{
            trigger_error("pouet");
        }

        $stmt->close();
        $conn->close();
        return $success;
    }

    // Déconnexion (réinitialiser les infos)
    public function disconnect(): void
    {
        $this->id = 0;
        $this->login = "";
        $this->password = "";
        $this->email = "";
        $this->firstname = "";
        $this->lastname = "";
    }

    // Suppression de l'utilisateur
    public function delete(): bool
    {
        $conn = $this->getConnection();
        $stmt = $conn->prepare("DELETE FROM utilisateurs WHERE id = ?");
        $stmt->bind_param("i", $this->id);
        $success = $stmt->execute();
        $stmt->close();
        $conn->close();

        if ($success) {
            $this->disconnect(); // vider les infos après suppression
        }
        
        return $success;
    }

    // Vérifie si l'utilisateur est connecté (authentifié)
    public function isConnected(): bool
    {
        return $this->id !== 0;
    }

    // Accesseurs
    public function getAllInfos(): array
    {
        return [
            'id' => $this->id,
            'login' => $this->login,
            'email' => $this->email,
            'firstname' => $this->firstname,
            'lastname' => $this->lastname
        ];
    }

    public function getId(): int { return $this->id; }
    public function getLogin(): string { return $this->login; }
    public function getEmail(): string { return $this->email; }
    public function getFirstname(): string { return $this->firstname; }
    public function getLastname(): string { return $this->lastname; }
}
?>
