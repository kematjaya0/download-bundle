# download-bundle for symfony 4 / symfony 5
1. install 
   `
   composer require kematjaya/download-bundle
   `
2. udpate config/bundles.php
   `
   Kematjaya\DownloadBundle\DownloadBundle::class => ['all' => true]
   `
3. create file config/packages/kmj_download.yml
   `
   download:
    upload_dir: '%kernel.project_dir%/public/uploads'
    encrypt: 
        key: ~ # encrypt key example: def00000ba99e2fe8c765adc1f915c5a8b40e1b34fb4324bfff75767725e3e1fb26b939ac4b9771bc16f864ccf1894c9dc452e8b2712448be826be0b7da6f464f78e8964
   `
4. import routing config/routes/annotations.yaml
  `
  kmj_download:
    resource: '@DownloadBundle/Resources/config/routing.xml'
  `
5. using template in twig config/packages/twig.yaml
  `
  twig:
    form_themes: [
      '@Download/upload_theme.html.twig'
    ]
  `
6. implement to entity e.g. App\Entity\Person
  `
  namespace App\Entity;
  
  use Kematjaya\DownloadBundle\Entity\DownloadInterface;
  
  /**
  * @ORM\Table(name="person")
  * @ORM\Entity(repositoryClass="App\Repository\PersonRepository")
  */
  class Person implements DownloadInterface
  {
  
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $images;
    
    public function getImages(): ?string
    {
        return $this->images;
    }

    public function setImages(?string $images): self
    {
        $this->images = $images;

        return $this;
    }
    
    public static function getPaths():array
    {
        return [
            'getImages' => 'person'  // 'getImages' is function where contain file name, and 'person' is path where file uploaded inside 'upload_dir' (part 3)
        ];
    }
  }
  `
7. dont forget to add upload file type in Form class e.g App\Form\PersonType
  `
  namespace App\Form;
  
  use App\Entity\Person;
  use Symfony\Component\Form\AbstractType;
  use Symfony\Component\Form\FormBuilderInterface;
  use Symfony\Component\Form\Extension\Core\Type\FileType;
  use Symfony\Component\OptionsResolver\OptionsResolver;
  
  class PersonType extends AbstractType
  {
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
      $builder->add('images', FileType::class);
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Person::class,
        ]);
    }
  }
  `

