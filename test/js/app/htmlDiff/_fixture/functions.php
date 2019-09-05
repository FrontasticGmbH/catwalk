<?php

function anonymizeDiff(array $diff)
{
    foreach ($diff as $diffAction) {
        switch ($diffAction->action) {
            case 'addElement':
            case 'removeElement':
                anonymizeHtml($diffAction->element);
                break;
            case 'replaceElement':
                anonymizeHtml($diffAction->oldValue);
                anonymizeHtml($diffAction->newValue);
                break;
            case 'addTextElement':
                $diffAction->value = anonymizeString($diffAction->value);
                break;
            case 'addAttribute':
                $diffAction->value = anonymizeAttribute($diffAction->name, $diffAction->value);
                break;
            case 'modifyAttribute':
                $diffAction->oldValue = anonymizeAttribute($diffAction->name, $diffAction->oldValue);
                $diffAction->newValue = anonymizeAttribute($diffAction->name, $diffAction->newValue);
                break;
        }
    }
}

function anonymizeHtml(\stdClass $htmlNode)
{
    if ($htmlNode->nodeName === '#text') {
        $htmlNode->data = anonymizeString($htmlNode->data);
    }

    if (isset($htmlNode->attributes) && is_object($htmlNode->attributes)) {
        foreach ($htmlNode->attributes as $attributeName => $attributeValue) {
            $htmlNode->attributes->$attributeName = anonymizeAttribute(
                $attributeName,
                $attributeValue
            );
        }
    }

    if (isset($htmlNode->childNodes) && is_array($htmlNode->childNodes)) {
        foreach ($htmlNode->childNodes as $childNode) {
            anonymizeHtml($childNode);
        }
    }
}

function anonymizeAttribute(string $attributeName, string $attributeValue): string
{
    switch ($attributeName) {
        case 'src':
            return 'https://example.com/src';
        case 'href':
            return 'https://example.com/href';
        case 'alt':
            return anonymizeString($attributeValue);
        case 'title':
            return anonymizeString($attributeValue);
        case 'class':
            return preg_replace(
                '(' . preg_quote(PROJECT) . ')i',
                'project',
                $attributeValue
            );
        case 'data-app':
        case 'data-props':
            return '';
    }

    return $attributeValue;
}

function anonymizeString(string $text): string
{
    return preg_replace('(\w)', 'X', $text);
}
