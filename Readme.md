<!-- Improved compatibility of back to top link: See: https://github.com/othneildrew/Best-README-Template/pull/73 -->
<a name="readme-top"></a>



<!-- PROJECT SHIELDS -->
<!--
*** I'm using markdown "reference style" links for readability.
*** Reference links are enclosed in brackets [ ] instead of parentheses ( ).
*** See the bottom of this document for the declaration of the reference variables
*** for contributors-url, forks-url, etc. This is an optional, concise syntax you may use.
*** https://www.markdownguide.org/basic-syntax/#reference-style-links
-->
[![Contributors][contributors-shield]][contributors-url]
[![Forks][forks-shield]][forks-url]
[![Stargazers][stars-shield]][stars-url]
[![Issues][issues-shield]][issues-url]
[![MIT License][license-shield]][license-url]
[![LinkedIn][linkedin-shield]][linkedin-url]



<!-- PROJECT LOGO -->
<br />
<div align="center">
  <a href="https://github.com/github_username/repo_name">
    <img src="images/logo.png" alt="Logo" width="80" height="80">
  </a>

<h3 align="center">registry</h3>

  <p align="center">
    This project adds a small reuseable registry for global options to a flow project
    <br />
    <a href="https://github.com/fucodo/registry"><strong>Explore the docs »</strong></a>
    <br />
    <br />
    <a href="https://github.com/fucodo/registry">View Demo</a>
    ·
    <a href="https://github.com/fucodo/registry/issues">Report Bug</a>
    ·
    <a href="https://github.com/fucodo/registry/issues">Request Feature</a>
  </p>
</div>



<!-- TABLE OF CONTENTS -->
<details>
  <summary>Table of Contents</summary>
  <ol>
    <li>
      <a href="#about-the-project">About The Project</a>
      <ul>
        <li><a href="#built-with">Built With</a></li>
      </ul>
    </li>
    <li>
      <a href="#getting-started">Getting Started</a>
      <ul>
        <li><a href="#prerequisites">Prerequisites</a></li>
        <li><a href="#installation">Installation</a></li>
      </ul>
    </li>
    <li><a href="#usage">Usage</a></li>
    <li><a href="#roadmap">Roadmap</a></li>
    <li><a href="#contributing">Contributing</a></li>
    <li><a href="#license">License</a></li>
    <li><a href="#contact">Contact</a></li>
    <li><a href="#acknowledgments">Acknowledgments</a></li>
  </ol>
</details>



<!-- ABOUT THE PROJECT -->
## About The Project

[![Product Name Screen Shot][product-screenshot]](https://example.com)

The project is developer centered, you can use it in any flow project.
It created a repository and a service to access the registry.

You may inspect the registry from the commandline.

<p align="right">(<a href="#readme-top">back to top</a>)</p>



### Built With

* Love

<p align="right">(<a href="#readme-top">back to top</a>)</p>



<!-- GETTING STARTED -->
## Getting Started

### Prerequisites

This is an example of how to list things you need to use the software and how to install them.
* npm
  ```sh
  composer req fucodo/registry
  ```

<p align="right">(<a href="#readme-top">back to top</a>)</p>



<!-- USAGE EXAMPLES -->
## Usage

Currently you may directly use the repository, we will add a service.

```php
<?php
namespace Vendor\Project\Controller;

/*
 * This file is part of the SBS.LaPo.Helparea package.
 */

use fucodo\registry\Domain\Repository\RegistryEntryRepository;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Mvc\Controller\ActionController;
use Vendor\Project\Domain\Model\HelpMessage;

class EditorController extends ActionController
{
    /**
     * @Flow\Inject
     * @var RegistryEntryRepository
     */
    protected $registry;

    /**
     * @return void
     */
    public function indexAction()
    {
        $this->view->assign('helpMessage', $this->registry->getValue('Vendor.Project', 'HelpMessage', new HelpMessage()));
    }
}
```

The given example uses `Neos.Flow` Dependency Injection to include the registry repository.

Then the `getValue` method is called with the namespace `Vendor.Project` and the name `Helpmessage`.
As the registry can also store objects, we use serialize internally in doctrine.

To set the value you can use the `setValue` method.

```
$this->registry->set('Vendor.Project', 'HelpMessage', $helpMessage);
```

The object `\Vendor\Project\Domain\Model\HelpMessage` is just a Data Transfer Object in this example,
to combine several properties in a single object.

Internally the registry also provides additional information regarding the registry entry.

```
createdAt - a DateTimeImmutable representing the creation date of the entry
updatedAt - a DateTimeImmutable representing the last update date of the entry
type      - a string representing the datatype in the database
```

The package also provides some cli commands:

```
  ./flow registry:list                            
  ./flow registry:set
```

Please use the `./flow help` command to get the detailed description of the commands.

<p align="right">(<a href="#readme-top">back to top</a>)</p>



<!-- ROADMAP -->
## Roadmap

- [x] Repo and Database access
- [x] CLI inspector
- [ ] Abstracted Service

See the [open issues](https://github.com/fucodo/registry/issues) for a full list of proposed features (and known issues).

<p align="right">(<a href="#readme-top">back to top</a>)</p>



<!-- CONTRIBUTING -->
## Contributing

Contributions are what make the open source community such an amazing place to learn, inspire, and create. Any contributions you make are **greatly appreciated**.

If you have a suggestion that would make this better, please fork the repo and create a pull request. You can also simply open an issue with the tag "enhancement".
Don't forget to give the project a star! Thanks again!

1. Fork the Project
2. Create your Feature Branch (`git checkout -b feature/AmazingFeature`)
3. Commit your Changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the Branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

<p align="right">(<a href="#readme-top">back to top</a>)</p>



<!-- LICENSE -->
## License

Distributed under the MIT License. See `LICENSE.txt` for more information.

<p align="right">(<a href="#readme-top">back to top</a>)</p>



<!-- CONTACT -->
## Contact

Your Name - [@twitter_handle](https://twitter.com/fucodo) - hello@fucodo.de

Project Link: [https://github.com/fucodo/registry](https://github.com/github_username/repo_name)

<p align="right">(<a href="#readme-top">back to top</a>)</p>



<!-- ACKNOWLEDGMENTS -->
## Acknowledgments

TBD.

<p align="right">(<a href="#readme-top">back to top</a>)</p>



<!-- MARKDOWN LINKS & IMAGES -->
<!-- https://www.markdownguide.org/basic-syntax/#reference-style-links -->
[contributors-shield]: https://img.shields.io/github/contributors/fucodo/registry.svg?style=for-the-badge
[contributors-url]: https://github.com/fucodo/registry/graphs/contributors
[forks-shield]: https://img.shields.io/github/forks/fucodo/registry.svg?style=for-the-badge
[forks-url]: https://github.com/fucodo/registry/network/members
[stars-shield]: https://img.shields.io/github/stars/fucodo/registry.svg?style=for-the-badge
[stars-url]: https://github.com/fucodo/registry/stargazers
[issues-shield]: https://img.shields.io/github/issues/fucodo/registry.svg?style=for-the-badge
[issues-url]: https://github.com/fucodo/registry/issues
[license-shield]: https://img.shields.io/github/license/fucodo/registry.svg?style=for-the-badge
[license-url]: https://github.com/fucodo/registry/blob/master/LICENSE.txt
[linkedin-shield]: https://img.shields.io/badge/-LinkedIn-black.svg?style=for-the-badge&logo=linkedin&colorB=555
[linkedin-url]: https://linkedin.com/in/linkedin_username
[product-screenshot]: Documentation/Images/screenshot.png
