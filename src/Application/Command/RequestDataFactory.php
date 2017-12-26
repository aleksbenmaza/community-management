<?php
/**
 * Created by PhpStorm.
 * User: alexandremasanes
 * Date: 21/12/2017
 * Time: 21:00
 */

namespace Application\Command;


use Doctrine\Common\Cache\CacheProvider;
use Symfony\Component\VarDumper\VarDumper;

class RequestDataFactory {

    private static $created = FALSE;

    private $metadata;

    private $cache;

    public static function create(CacheProvider $cacheProvider): self {
        if(self::$created)
            throw new \RuntimeException(sprintf('%s cannot be instantiated more than once !', self::class));
        self::$created = TRUE;
        return new self($cacheProvider);
    }

    private function __construct(CacheProvider $cacheProvider) {
        $this->cache = $cacheProvider;
        $this->metadata = [];
    }

    public function map(string $command_class, array & $request) {

        $setter = '$set';
        $command_object = new $command_class;
        self::parseFieldsAnnotations($command_class);

        foreach ($this->metadata[$command_class] as $property_name => $metadata) {

            if (isset($request[$metadata->field_name])) {
                $value = $request[$metadata->field_name];
                if ($metadata->field_type == 'datetime')
                    $value = new \DateTime($value);

                $last_pos = 0;
                while ((($last_pos = strpos($property_name, '_', $last_pos))) !== false) {
                    $property_name[$last_pos + 1] = ucfirst($property_name->property_name[$last_pos + 1]);
                    $property_name = str_replace('_', '', $property_name);
                }

                $setter .= ucfirst(str_replace('_', '', $property_name));
                $command_object->$setter($value);
                $setter = 'set';
            }
            unset($request[$metadata->field_name]);
        }

        return $command_object;
    }

    private function parseFieldsAnnotations(string $request_class): void {
        if(apcu_exists($key = 'request.cache')) {
            $this->metadata = apcu_fetch($key);
            if(isset($this->metadata[$request_class]))
                return;
        }
        $reflect = new \ReflectionClass($request_class);
        $fields  = [];
        foreach($reflect->getProperties() as $prop) {
            $doc_comment = $prop->getDocComment();
            $doc_comment = trim($doc_comment, '/');
            $doc_comment = str_replace('**', '', $doc_comment);
            $doc_comment = str_replace('*', ';', $doc_comment);
            $doc_comment = trim($doc_comment);
            $doc_comment = trim($doc_comment, ';');
            $doc_comment = trim($doc_comment);
            $annotations = explode(';', $doc_comment);
            $metadata = NULL;
            foreach ($annotations as $i => $annotation) {
                $annotation = trim($annotation, '" ');
                $annotation = trim($annotation);

                if (preg_match_all('/^(?P<annotation>@[\s\S]+)$/', $doc_comment, $_)) {
                    if (!isset($annotation[0]) || $annotation[0] != '@')
                        continue;
                    $annotation = trim($annotation, '@');
                    preg_match_all('/\w\(([\s\S]*)\)/', $annotation, $matches);

                    if(strpos($annotation, '(') !== FALSE) {

                        $annotation_name = explode('(', $annotation)[0];

                        $annotation = str_replace($annotation_name, '', $annotation);

                        $annotation_values_tmp = explode('=', trim($annotation, '()'));
                        $annotation_properties = [
                            "name" => NULL,
                            "type" => NULL
                        ];
                        for($i = 0; $i < count($annotation_values_tmp); $i += 2)
                            $annotation_properties[$annotation_values_tmp[$i]] = $annotation_values_tmp[$i + 1];
                    } else {
                        $annotation_name = $annotation;
                        $annotation_properties = [
                            "name" => NULL,
                            "type" => NULL
                        ];
                    }

                    if ($annotation_name == 'Field') {
                        $field_metadata = new \stdClass;

                        foreach($annotation_properties as $property_name => $property_value) {
                            $property_name  = trim($property_name);
                            $property_value = trim($property_value);
                            switch($property_name) {
                                case 'name':
                                    $field_metadata->field_name = $property_value ? $property_value : $prop->getName();
                                    break;

                                case 'type':
                                    $field_metadata->field_type = $property_value ? $property_value : 'text';
                                    break;

                            }
                        }
                        $this->metadata[$request_class][$prop->getName()] = $field_metadata;
                    }
                }
            }
        }

        $this->cache->save('request.cache', $this->metadata);
    }

}