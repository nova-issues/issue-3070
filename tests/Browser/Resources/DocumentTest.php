<?php

namespace Tests\Browser\Resources;

use App\Models\Document;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Dusk\Browser;
use Laravel\Nova\Testing\Browser\Pages\Create;
use Tests\DuskTestCase;

class DocumentTest extends DuskTestCase
{
    use DatabaseTransactions;

    /**
    * A Dusk test example.
    *
    * @return void
    */
    public function testCreateDocumentResource()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs(1)
                ->visit(new Create('documents'))
                ->waitForText('Create Document', 60)
                ->type('@name', 'test-document')
                ->attach('@file', base_path('storage/seeds/Document.pdf'))
                ->create()
                ->waitForText('The document was created', 60);

            $document = Document::latest()->first();

            $this->assertSame('test-document', $document->name);
            $this->assertNotNull($document->file);
        });

        Document::query()->each(function ($document) {
            $document->delete();
        });
    }
}
