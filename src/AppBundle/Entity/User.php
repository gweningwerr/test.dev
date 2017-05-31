<?php

namespace AppBundle\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\PersistentCollection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Imagick;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 * @ORM\HasLifecycleCallbacks
 */
class User extends BaseUser
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="sex", type="smallint", nullable=true,
     * options={"comment" = "Пол: 1 - мужской, 2 - женский"})
     * @Assert\NotBlank(message="Укажите пол, это важно", groups={"Registration", "Profile"})
     */
    protected $sex;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="birth", type="date", nullable=true, nullable=true,
     * options={"comment" = "Дата рождения"})
     */
    protected $birth;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=1000, nullable=true,
     * options={"comment" = "Город проживания"})
     * @Assert\Length(
     *     min=2,
     *     max=255,
     *     minMessage="Не бывает города из одной буквы",
     *     maxMessage="Не бывает города с таким длинным названием",
     *     groups={"Registration", "Profile"}
     * )
     */
    protected $city;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=1000, nullable=true,
     * options={"comment" = "Адрес проживания"})
     */
    protected $address;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=1000, nullable=true,
     * options={"comment" = "Телефон"})
     */

    protected $phone;

    /**
     * @var PersistentCollection|Task[]
     *
     * @ORM\OneToMany(targetEntity="Task", mappedBy="author", cascade={"persist", "remove"})
     */
    protected $taskToMe;

    /**
     * @var PersistentCollection|Task[]
     *
     * @ORM\OneToMany(targetEntity="Task", mappedBy="performer", cascade={"persist", "remove"})
     */
    protected $taskMy;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="change_info", type="datetime", nullable=true,
     * options={"comment" = "Дата и время изменения данных"})
     */
    protected $changeInfo;

    /**
     * @var string
     *
     * @ORM\Column(name="filename", type="string", length=100, nullable=true)
     */
    private $fileName;

    /**
     * @var UploadedFile
     * @Assert\File(maxSize="6000000")
     */
    private $file;

    /**
     * @var string
     */
    private $tmp;


    public function __construct()
    {
        parent::__construct();

        $this->roles = ['ROLE_USER'];
        $this->taskToMe = new ArrayCollection();
        $this->taskMy = new ArrayCollection();
    }

    public function getAbsolutePath () {
        return __DIR__ . '/../../../web' . $this->getWebDir();
    }

    protected  function getWebDir () {
        return  '/users/';
    }

    public function getWebPath()
    {
        return empty($this->fileName) ? null : $this->getWebDir() . 'p_' . $this->fileName;
    }

    public function getWebPreviewPath()
    {
        return empty($this->fileName) ? null : $this->getWebDir() . $this->fileName;
    }

    protected function getUploadDir()
    {
        return $this->getAbsolutePath();
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        if (null !== $this->getFile()) {

            if ($this->fileName != null) {
                $this->tmp = $this->fileName;
            }

            $fileName = sha1(uniqid(mt_rand(), true));
            $this->fileName = $fileName . '.' . $this->getFile()->guessExtension();
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        if (null === $this->getFile()) {
            return;
        }

        $img = new Imagick($this->file->getPathname());
        $img->thumbnailImage(300, 300, true, false);
        $img->writeImage($this->getAbsolutePath() . $this->fileName);

        $img->cropThumbnailImage(180, 180);
        $img->writeImage($this->getAbsolutePath() . 'p_' . $this->fileName);

        if (!empty($this->tmp)) {
            $image = $this->getAbsolutePath() . $this->tmp;
            $imagePreview = $this->getAbsolutePath() . 'p_' . $this->tmp;

            if (file_exists($image)) {
                unlink($image);
            }

            if (file_exists($imagePreview)) {
                unlink($imagePreview);
            }
        }
    }

    /**
     * @ORM\PreRemove()
     */
    public function removeUpload()
    {
        $image = $this->getAbsolutePath() . $this->fileName;
        $imagePreview = $this->getAbsolutePath() . 'p_' . $this->fileName;

        if (file_exists($image)) {
            unlink($image);
        }

        if (file_exists($imagePreview)) {
            unlink($imagePreview);
        }

        $this->fileName = null;
    }

    /**
     * Sets ChangeInfo
     * @return User
     */
    public function setChangeInfo()
    {
        $this->changeInfo = new \DateTime("now");

        return $this;
    }

    public function getChangeInfo()
    {
        return $this->changeInfo;
    }

    /**
     * Sets file
     *
     * @param UploadedFile $file
     * @return User
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * Get file
     *
     * @return UploadedFile $file
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Set delFile
     *
     * @param string $delFile
     * @return User
     */
    public function setDelFile($delFile)
    {
        if ($delFile) {
            $this->removeUpload();
        }

        return $this;
    }

    /**
     * var
     *
     * @return bool
     */
    public function getDelFile () {
        return false;
    }

    /**
     * Set fileName
     *
     * @param string $fileName
     * @return User
     */
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;

        return $this;
    }

    /**
     * Get fileName
     *
     * @return string
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * Set sex
     *
     * @param boolean $sex
     *
     * @return User
     */
    public function setSex($sex)
    {
        $this->sex = $sex;

        return $this;
    }

    /**
     * Get sex
     *
     * @return boolean
     */
    public function getSex()
    {
        return $this->sex;
    }

    /**
     * Set birth
     *
     * @param \DateTime $birth
     *
     * @return User
     */
    public function setBirth($birth)
    {
        $this->birth = $birth;

        return $this;
    }

    /**
     * Get birth
     *
     * @return \DateTime
     */
    public function getBirth()
    {
        return $this->birth;
    }

    /**
     * Set city
     *
     * @param string $city
     *
     * @return User
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set address
     *
     * @param string $address
     *
     * @return User
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return User
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }


    /**
     * Add taskToMe
     *
     * @param Task $taskToMe
     *
     * @return User
     */
    public function addTaskToMe(Task $taskToMe)
    {
        $this->taskToMe[] = $taskToMe;

        return $this;
    }

    /**
     * Remove taskToMe
     *
     * @param Task $taskToMe
     */
    public function removeTaskToMe(Task $taskToMe)
    {
        $this->taskToMe->removeElement($taskToMe);
    }

    /**
     * Get taskToMe
     *
     * @return Collection
     */
    public function getTaskToMe()
    {
        return $this->taskToMe;
    }

    /**
     * Add taskMy
     *
     * @param Task $taskMy
     *
     * @return User
     */
    public function addTaskMy(Task $taskMy)
    {
        $this->taskMy[] = $taskMy;

        return $this;
    }

    /**
     * Remove taskMy
     *
     * @param Task $taskMy
     */
    public function removeTaskMy(Task $taskMy)
    {
        $this->taskMy->removeElement($taskMy);
    }

    /**
     * Get taskMy
     *
     * @return Collection
     */
    public function getTaskMy()
    {
        return $this->taskMy;
    }
}
