<?php

namespace Tecnoready\SFAdminLTE3Bundle\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Twig\Environment;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Tecnoready\Common\Util\StringUtil;

/**
 * Extension de admin lte
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class AdminLTE3Extension extends AbstractExtension
{
    /**
     * @var Environment
     */
    private $twig;
    
    /**
     * $translator
     * @var TranslatorInterface
     */
    private $translator;
    
    /**
     * @var RouterInterface
     */
    private $router;
    
    public function getFunctions()
    {
        return [
            new TwigFunction('breadcrumb', [$this, 'breadcrumb'], ["is_safe" => ["html"]]),
            new TwigFunction('build_with_referer', [$this, 'buildWithReferer']),
        ];
    }
    
    public function getFilters()
    {
        return [
            new TwigFilter('resolve_options', [$this, 'resolveOptions']),
            new TwigFilter('resolve_tooltip', [$this, 'resolveTooltip']),
        ];
    }
    
    /**
     * Le agrega la url de retorno o referido a una url actual
     * @param string $url
     * @param type $referer
     * @return string
     */
    public function buildWithReferer($url, $referer)
    {
        $toRemove = ["_referer"];
        $referer = StringUtil::removeQueryStringURL($referer,$toRemove);
        $url = StringUtil::removeQueryStringURL($url,$toRemove);

        $urlParsed = parse_url($url);
        $s = "?";
        if (strpos($url, $s) !== false) {
            $s = "&";
        }
        $url .= $s."_referer=". urlencode($referer);
        return $url;
    }
    
    /**
     * Renderiza un breadcrump
     * @param type $title
     * @param array $breadcrumbs {link:'',label:'','route': null}
     * @return string
     */
    public function breadcrumb($title, array $breadcrumbs)
    {
        $newBreadcrumbs = [];
        $resolver = new OptionsResolver();
        $resolver->setDefaults([
            "link" => null,
            "route" => null,
            "route_parameters" => [],
            "label_parameters" => [],
        ]);
        $resolver->setRequired("label");

        $newBreadcrumbs[] = [
            "label" => $this->trans("menu.home"),
            "link" => $this->generateUrl("p_main_index"),
        ];
        foreach ($breadcrumbs as $breadcrumb) {
            $values = $resolver->resolve($breadcrumb);
            if ($values["route"] !== null) {
                $values["link"] = $this->generateUrl($values["route"], $values["route_parameters"]);
            }
            $values["label"] = $this->trans($values["label"], $values["label_parameters"]);
            $newBreadcrumbs[] = $values;
        }

        return $this->twig->render("@SFAdminLTE3/default/breadcrumb.html.twig", [
                    "title" => $title,
                    "breadcrumbs" => $newBreadcrumbs,
        ]);
    }
    
    /**
     * Resuelve parametros para un tooltip
     * @param type $options
     * @return array
     */
    public function resolveTooltip($options)
    {
        if ($options == null) {
            $options = [];
        }
        $resolver = new OptionsResolver();
        $resolver->setDefaults([
            "data-html"=>"false",
            "data-toggle"=>"tooltip",
            "data-placement"=>"top",
        ]);
        $resolver->setDefined(["title"]);
        $resolver->setAllowedValues("data-placement",["top","right","bottom","left"]);
        $resolver->setRequired(["title"]);
        $options = $resolver->resolve($options);
        return $options;
    }
    
    /**
     * Resuelve las opciones definidas
     * @param array $defaults
     * @param type $options
     * @return array
     */
    public function resolveOptions($defaults, $options, array $extras = [])
    {
        //Permitir null y correguir en tiempo de ejecucion
        if (!is_array($defaults)) {
            $defaults = [];
        }
        $resolver = new OptionsResolver();
        $resolver->setDefaults($defaults);

        $resolverExtras = new OptionsResolver();
        $resolverExtras->setDefaults([
            "allowedValues" => [],
            "allowedTypes" => [],
            "required" => [],
        ]);
        $extras = $resolverExtras->resolve($extras);
//        var_dump($extras["required"]);
        $resolver->setRequired($extras["required"]);
        foreach ($extras["allowedValues"] as $option => $values) {
            $resolver->setAllowedValues($option, $values);
        }
        foreach ($extras["allowedTypes"] as $option => $allowedTypes) {
            $resolver->setAllowedTypes($option, $allowedTypes);
        }
        if ($options == null) {
            $options = [];
        }
        $options = $resolver->resolve($options);

        return $options;
    }
    
    /**
     * Generates a URL from the given parameters.
     *
     * @see UrlGeneratorInterface
     *
     * @final
     */
    protected function generateUrl(string $route, array $parameters = [], int $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH): string
    {
        return $this->router->generate($route, $parameters, $referenceType);
    }
    
    /**
     * @required
     * @param Router $router
     * @return $this
     */
    public function setRouter(RouterInterface $router)
    {
        $this->router = $router;
        return $this;
    }
    
    /**
     * @required
     * @param Environment $twig
     * @return $this
     */
    public function setTwig(Environment $twig)
    {
        $this->twig = $twig;
        return $this;
    }

    /**
     * @required
     */
    public function setTranslator(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * Traduce un indice
     * @param type $message
     * @param array $arguments
     * @param type $domain
     * @return type
     */
    protected function trans($message,array $arguments = array(), $domain = 'messages')
    {
        return $this->translator->trans($message, $arguments, $domain);
    }
    
}
