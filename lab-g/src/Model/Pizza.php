<?php
namespace App\Model;
use App\Service\Config;

class Pizza {
    private ?int $id = null;
    private ?string $name = null;
    private ?string $size = null;
    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): Pizza {
        $this->id = $id;
        return $this;
    }

    public function getName(): ?string {
        return $this->name;
    }

    public function setName(?string $name): Pizza {
        $this->name = $name;
        return $this;
    }

    public function getSize(): ?string {
        return $this->size;
    }

    public function setSize(?string $size): Pizza {
        $this->size = $size;
        return $this;
    }

    public static function fromArray($array): Pizza {
        $pizza = new self();
        $pizza->fill($array);
        return $pizza;
    }

    public function fill($array): Pizza {
        if (isset($array['id']) && ! $this->getId()) {
            $this->setId($array['id']);
        }
        if (isset($array['name'])) {
            $this->setName($array['name']);
        }
        if (isset($array['size'])) {
            $this->setSize($array['size']);
        }
        return $this;
    }

    public static function findAll(): array {
        $pdo = new \PDO(
            Config::get('db_dsn'),
            Config::get('db_user'),
            Config::get('db_pass')
        );
        $sql = 'SELECT * FROM pizza';
        $statement = $pdo->prepare($sql);
        $statement->execute();
        $pizzas = [];
        $pizzasArray = $statement->fetchAll(\PDO::FETCH_ASSOC);
        foreach ($pizzasArray as $pizzaArray) {
            $pizzas[] = self::fromArray($pizzaArray);
        }
        return $pizzas;
    }

    public static function find($id): ?Pizza {
        $pdo = new \PDO(
            Config::get('db_dsn'),
            Config::get('db_user'),
            Config::get('db_pass')
        );
        $sql = 'SELECT * FROM pizza WHERE id = :id';
        $statement = $pdo->prepare($sql);
        $statement->execute([
            'id' => $id,
        ]);
        $pizzaArray = $statement->fetch(\PDO::FETCH_ASSOC);
        if (! $pizzaArray) {
            return null;
        }
        return self::fromArray($pizzaArray);
    }

    public function save(): void {
        $pdo = new \PDO(
            Config::get('db_dsn'),
            Config::get('db_user'),
            Config::get('db_pass')
        );
        if (! $this->getId()) {
            $sql = "
                INSERT INTO pizza (name, size)
                VALUES (:name, :size)
            ";
            $statement = $pdo->prepare($sql);
            $statement->execute([
                'name' => $this->getName(),
                'size' => $this->getSize(),
            ]);
            $this->setId($pdo->lastInsertId());
        } else {
            $sql = "
                UPDATE pizza
                SET name = :name,
                    size = :size
                WHERE id = :id
            ";
            $statement = $pdo->prepare($sql);
            $statement->execute([
                ':name' => $this->getName(),
                ':size' => $this->getSize(),
                ':id' => $this->getId(),
            ]);
        }
    }
    public function delete(): void {
        $pdo = new \PDO(
            Config::get('db_dsn'),
            Config::get('db_user'),
            Config::get('db_pass')
        );
        $sql = "DELETE FROM pizza WHERE id = :id";
        $statement = $pdo->prepare($sql);
        $statement->execute([
            ':id' => $this->getId(),
        ]);
        $this->setId(null);
        $this->setName(null);
        $this->setSize(null);
    }
}