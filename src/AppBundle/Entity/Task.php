<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Task
 *
 * @ORM\Table(name="task")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TaskRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Task
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_create", type="date", nullable=true,
     * options={"comment" = "Дата рождения"})
     */
    protected $dateCreate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_close", type="date", nullable=true,
     * options={"comment" = "Дата рождения"})
     */
    protected $dateClose;
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     * options={"comment" = "Название задачи"})
     * @Assert\NotBlank(message="Введите название задачи", groups={"Task"})
     */
    private $name;

    /**
     * @var ListStatus
     *
     * @ORM\ManyToOne(targetEntity="ListStatus", cascade={"persist"})
     * @ORM\JoinColumn(name="status", referencedColumnName="id", onDelete="SET NULL")
     * options={"comment" = "Статус задачи"}
     */
    private $status;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=1000)
     * options={"comment" = "Описание задачи"})
     * @Assert\NotBlank(message="Введите описание задачи", groups={"Task"})
     */
    private $description;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User", cascade={"persist"})
     * @ORM\JoinColumn(name="author", referencedColumnName="id", onDelete="SET NULL")
     * options={"comment" = "Автор задачи"}
     */
    private $author;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User", cascade={"persist"})
     * @ORM\JoinColumn(name="performer", referencedColumnName="id", onDelete="SET NULL")
     * options={"comment" = "Исполнитель задачи"}
     * @Assert\NotBlank(message="Необходимо выбрать исполнителя задачи", groups={"Task"})
     */
    private $performer;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Task
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Task
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set dateCreate
     *
     * @return Task
     */
    public function setDateCreate()
    {
        $this->dateCreate = new \DateTime("now");

        return $this;
    }

    /**
     * Get dateCreate
     *
     * @return \DateTime
     */
    public function getDateCreate()
    {
        return $this->dateCreate;
    }

    /**
     * Set dateClose
     *
     * @param \DateTime $dateClose
     *
     * @return Task
     */
    public function setDateClose($dateClose)
    {
        $this->dateClose = $dateClose;

        return $this;
    }

    /**
     * Get dateClose
     *
     * @return \DateTime
     */
    public function getDateClose()
    {
        return $this->dateClose;
    }

    /**
     * Set status
     *
     * @param ListStatus $status
     *
     * @return Task
     */
    public function setStatus(ListStatus $status = null)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return ListStatus
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set author
     *
     * @param User $author
     *
     * @return Task
     */
    public function setAuthor(User $author = null)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return User
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set performer
     *
     * @param User $performer
     *
     * @return Task
     */
    public function setPerformer(User $performer = null)
    {
        $this->performer = $performer;

        return $this;
    }

    /**
     * Get performer
     *
     * @return User
     */
    public function getPerformer()
    {
        return $this->performer;
    }
}
