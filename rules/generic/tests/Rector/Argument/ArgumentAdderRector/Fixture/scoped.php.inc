<?php

namespace Rector\Generic\Tests\Rector\Argument\ArgumentAdderRector\Fixture;

use Rector\Generic\Tests\Rector\Argument\ArgumentAdderRector\Source\SomeParentClient;

class Scoped extends SomeParentClient
{
    public function submit(\DomCrawlerForm $form, array $values = [])
    {
        return parent::submit($form, $values);
    }

    public function selfie($form)
    {
        $self = new self();
        $self->submit($form);
    }
}

?>
-----
<?php

namespace Rector\Generic\Tests\Rector\Argument\ArgumentAdderRector\Fixture;

use Rector\Generic\Tests\Rector\Argument\ArgumentAdderRector\Source\SomeParentClient;

class Scoped extends SomeParentClient
{
    public function submit(\DomCrawlerForm $form, array $values = [], array $serverParameters = [])
    {
        return parent::submit($form, $values, $serverParameters);
    }

    public function selfie($form)
    {
        $self = new self();
        $self->submit($form);
    }
}

?>
