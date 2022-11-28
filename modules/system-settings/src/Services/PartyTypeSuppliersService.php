<?php


namespace SkylarkSoft\GoRMG\SystemSettings\Services;

class PartyTypeSuppliersService
{
    private function data(): array
    {
        return [
            'AOP Subcontract',
            'Accessories Supplier',
            'Buyer/Supplier',
            'C & F Agent',
            'Civil Contractor',
            'Clearing Agent',
            'Dyeing/Finishing Subcontract',
            'Dyes & Chemical Supplier',
            'Embellishment Supplier',
            'Fabric Supplier',
            'Fabric Washing Subcontract',
            'Forwarding Agent',
            'Garments Subcontract',
            'General Item',
            'Grey Fabric Service Subcontract',
            'Indentor',
            'Inspection',
            'Interior',
            'Knit Subcontract',
            'Lab Test Company',
            'Labor Contractor',
            'Loan Party',
            'Machineries Supplier',
            'Other Contractor',
            'Re-Waxing',
            'Stationery Supplier',
            'Supplier',
            'Transport Supplier',
            'Trims Sub-Contract',
            'Trims Supplier',
            'Twisting',
            'Vehicle Components',
            'Yarn Supplier',
            'Fabrics',
            'Swinger',
            'Poly',
            'Trims (Sewing Thread)',
            'Trims (POS and carton sticker)',
            'Trims( Carton)',
            'Trims (Barcode and carton sticker)',
            'Screen Printing',
            'Embroidery',
            'Trims (Poly)',
            'Trims (Main label,care label,hang tag)',
            'Trims (Tag pin, Lock Pin, Tissue, Mobilon tape, Satin Hanger Loop, Woven fabrics, Cable Tie)',
            'hang tag and sticker',
            'Trims(Zipper)',
            'Elastic, Draw cord, Twill Tape, Sewing Thread',
            'Trims',
            'Leather Patch',
            'Button',
            'Super Dry',
            'Accessories',
            'Washing',
            'TRIMS & ACCESSORIES',
            'SECURITY ALARM /SHEET ALARM',
            'Trims (Sewing Thread)',
            'Trims( RFID, Non-RFID, Price sticker, Bercode sticker,front card)',
            'Trims (Care label, Swinger, Bercode sticker, hangtag String, poly sticker, carton sticker)',
            'Trims (Carton , Top & BTM)',
            'Trims (Carton, gum tape, poly)',
            'Trims (Main label, hangtag, Size label)',
            'Trims (Gum Tape, Poly sticher, carton Sticker, Heat seal sticker, Fold Elastic tape, Rider, Woven patch label, main label, Elastic, Poly)',
            'Trims(Eyelet, Snap button, plastic tipping, metal tipping, plastic bullet, metal bullet,Stoper, Darwcard, Silicon rubber patch)',
            'Trims (Care label, Main label, Sewing Ticket, Boxen label, Poly sticker, Casecade, Size label,security tag)',
            'Trims(Hanger, Hanger Hook, Drop Loop)',
            'Trims (Care label, Main label, Sewing Ticket, Boxen label, Poly sticker, Casecade, size label)',
            'Trims (Hanger, Hanger sizer, Janger, Drop loop, Secuirity tag)',
            'Trims (Lurex Twill tape,,Tips attachment, pompom, flower)',
            'Interliling, Tycot',
            'Trims(Eyelet, Snap button, plastic tipping, metal tipping, plastic bullet, metal bullet,Stoper, Darwcard, Silicon rubber patch)',
            'Trims (Gum Tape, Poly sticher, carton Sticker, Heat seal sticker, Fold Elastic tape, Rider, Woven patch label, main label, Elastic, Poly)',
            'Zipper, Zipper puller',
            'Trims (Zipper, Zipper puller)',
            'Trims(Woven Main label, size label, silicon label)',
            'Trims(Hanger loop, gross gain tape, woven tape)',
            'Trims(Button, Carton)',
        ];
    }

    public function partyTypes(): \Illuminate\Support\Collection
    {
        $data = $this->data();

        return collect($data)->map(function ($party) {
            return [
                'key' => $party,
                'value' => $party,
            ];
        })->pluck('value', 'key');
    }
}



