<?php use Cake\I18n\Number; ?>
<style>
    @page {
        /* ensure you append the header/footer name with 'html_' */
        header: html_MyCustomHeader; /* sets <htmlpageheader name="MyCustomHeader"> as the header */   
        footer: html_MyCustomFooter; /* sets <htmlpagefooter name="MyCustomFooter"> as the footer */		
        margin-top: 200px;
        margin-bottom:80px
    }
</style>

<div class="pdfSection">
    <htmlpageheader name="MyCustomHeader">
        <img src="<?php echo SITE_FULL_URL; ?>/img/pacelogo-220.png" />
        <ul style="width:1000px;" class="pdfHeaderTOP">
            <li style="float:left;width:110px;font-size:10px;padding-bottom:5px;">T: (844) USE - PACE<br />F: 408-317-0381 <br />www.pacefunding.com</li>
            <li style="float:right;width:500px;" class="pdfMainHeading"><br />Completion Certificate</li>
            <div style="clear:both;"></div>
        </ul>
        <hr />
    </htmlpageheader>

    <htmlpagefooter name="MyCustomFooter">
        <div class="pdfFooterSection">
            <hr />
            <ul class="pdfLastSection">
                <li style="font-size:11px;float:left;width:48%;">Application ID: <?php echo $ApplicationID; ?></li>
                <li style="font-size:11px;float:right;text-align:right;width:51%;">PACE Funding Completion Certificate<br />v1.2 – <?php echo date('F, Y'); ?></li>
                <div style="clear:both;"></div>
            </ul>
        </div>
    </htmlpagefooter>

    <h2>Instructions</h2>	
    <p>
        After all work has been completed, the contractor and all property owners must sign this Completion Certificate. This Completion Certificate and all other required attachments must then be submitted to the Program Administrator. The Program Administrator will then approve the Completion Certificate and process the payment.
    </p>
    <table cellspacing="0" class="ft" style="border:1px solid #000;width:100%">
        <tr class="ftfr">
            <td width="33%" height="30px" style="border-left:none;" ><span>Property Owner(s):<br /><?php echo $owner1; echo (isset($owner2) && $owner2!='')?" And ".$owner2:''; ?><br /></span></td>
            <td width="33%" height="30px"><span>Property Value (AVM):<br /><?php echo $PropertyValuation; ?><br /></span></td>
            <td width="33%" height="30px"></td>
        </tr>
        <tr>
            <td colspan="3" height="30px" style="border-left:none;" ><span>Property Address:<br /><?php echo $AddressProperty; ?><br /></span></td>
        </tr>
        <tr>
            <td width="33%" height="30px" style="border-left:none;"><span>City:<br /><?php echo $CityProperty; ?><br /></span></td>
            <td width="33%" height="30px"><span>State:<br /><?php echo $StateProperty; ?><br /></span></td>
            <td width="33%" height="30px">Zip:<br /><?php echo $ZipProperty; ?><br /></td>
        </tr>
        <tr >
            <td width="33%" height="30px" style="border-left:none;"><span>Application ID:<br /><?php echo $ApplicationID; ?><br /></span></td>
            <td colspan="2" width="66%" height="30px"><span>Application Date:<br /><?php echo $creditApprovedDate; ?><br /></span></td>
        </tr>        
    </table>

    <h2>Final Eligible Product Verification</h2>	

    <table  style="border:1px solid #000;width:100%" cellpadding="0" cellspacing="0">   
    <thead>
        <tr class="borderDisplayMain notopBorder" style="border-left: none!important;">
            <td style="border-left: none!important;"><b>Product Type</b></td>
            <td><b>Manufacturer</b></td>
            <td><b>Model</b></td>
            <td><b>SKU</b></td>
            <td><b>Quantity</b></td>
            <td><b>Cost</b></td>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($project['contract_detail']['equipment_informations'] as $eq_info) {?>
        <tr class="borderDisplayMain" style="border-left: none!important;">
            <td style="border-left: none!important;"><?php echo $eq_info['project_type']['name']; ?></td>
            <td><?php echo $eq_info['manufacturer']; ?></td>
            <td><?php echo $eq_info['model']; ?></td>
            <td><?php echo $eq_info['sku']; ?></td>
            <td class="vsmall-text-filed">
            <?php echo $eq_info['qty']; ?>
            </td>
            <td class="small-text-filed">
            <?php echo Number::currency($eq_info['amount'], 'USD'); ?>
            </td>
        </tr> 
        <?php } ?>
    </tbody>
</table>
    <br>
    <table cellspacing="0" class="st" style="border:1px solid #000;width:100%">
        <tr class="stfr">
            <td width="50%" height="30px" style="border-left:none;"><h2>Contractor</h2></td>
            <td width="50%" height="30px">&nbsp;</td>
        </tr>
        <tr>
            <td width="50%" height="30px" style="border-left:none;">Company Name: <?php echo $ContractorCompanyName; ?></td>
            <td width="50%" height="30px">Phone: <?php echo $ContractorPhone; ?></td>
        </tr>
        <tr>
            <td width="50%" height="30px" style="border-left:none;">CSLB#: <?php echo $ContractorLicense; ?></td>
            <td width="50%" height="30px">Address: <?php echo $ContractorAddress; ?></td>
        </tr>
    </table>
    
 <pagebreak />
 <p>I, the undersigned, certify that:</p>
 <ul class="pdfListNumbered">
     <li>The products installed on the property are complete to the satisfaction of the Property Owner;</li>
     <li>The Property Owner signed this Completion Certificate after the installation of the products and all signatures on this   Certificate are genuine;</li>
     <li>I, or my subcontractor(s), have the correct licensing/classifications from the Contractor State Licensing Board to install the products listed on this Completion Certificate;</li>
     <li>I have the authority to sign this Completion Certificate on behalf of my company;</li>
     <li>Upon submission of this Completion Certificate to the PACE Funding Program Administrator, I will attach true and correct copies of the permit(s) and final invoice(s) and, if applicable, the final Pre- Paid Solar Agreement;</li>
     <li>I understand that the Property Owner is responsible for any of the invoiced cost of the product(s) even if it is greater than the amount financed through the Program;</li>
     <li>I waive and release the lien, stop payment notice, and payment bond rights for labor and services provided, and equipment and material delivered, to the Property Owner on this job. Rights based
upon labor or service provided, or equipment or material delivered, pursuant to a written change order that has been fully executed by the parties prior to the date that this document is signed, are waived and released by this document;</li>
     <li>I understand that PACE Funding Group LLC or its affiliates, will disburse payment to me if indicated by the Property Owner in Section B.2 “Assign Payment;” and,</li>
     <li>I hereby transfer and assign my right to the Program Fund moneys to PACE Funding LLC or its affiliates for the Financed Project Amount.</li>
 </ul>
 <br>
 <ul class="pdfLastSection">
            <li style="float:left;width:65%;">Contractor Signature: ______________</li>
            <li style="float:right;width:35%;"> Date: ______________</li>
        </ul>
        <p>Printed Name: <?php echo $ContractorName; ?></p>
 
 <h2>Payment Assignment</h2>	
 
 <table cellspacing="0" class="st" style="border-left:1px solid #000;border-right:1px solid #000;border-bottom:1px solid #000;width:100%">
        <tr>
            <td width="50%" height="30px" style="border-left:none;">Company Name: <?php echo $ContractorCompanyName; ?></td>
            <td width="50%" height="30px">Phone: <?php echo $ContractorPhone; ?></td>
        </tr>
        <tr>
            <td width="50%" height="30px" style="border-left:none;">Contact Name: <?php echo $ContractorLicense; ?></td>
            <td width="50%" height="30px">Address: <?php echo $ContractorAddress; ?></td>
        </tr>
        <tr>
            <td width="50%" height="30px" style="border-left:none;">Total Financed Project Amount:</td>
            <td width="50%" height="30px"><?php echo Number::currency($TotalProjectCost, 'USD'); ?></td>
        </tr>
    </table>
 <p style="font-size: 10px;"><sup>1</sup>Payment to a Participating Contractor will be disbursed two business days following the date of submittal, if the Completion Certificate is received on any business day by 10am PST.
     <br> <sup>2</sup>Financed Project Amount must be indicated on the Final Invoice.</p>
 <pagebreak />
 <h2>Property Owner(s)</h2>
 <p>Property Owner Name(s):<br><?php echo $owner1; echo (isset($owner2) && $owner2!='')?", ".$owner2:''; ?></p>
 <p>I, the undersigned, certify that:</p>
 <ul class="pdfListNumbered">
     <li>The products installed on my property are completed to my satisfaction;</li>

<li>I understand that the selection of the Participating Contractor and acceptance of the materials used and the work performed is my responsibility and that CSCDA, purchasers of any bonds issued by CSCDA, and the Program Administrator, do not endorse any contractor or any other person involved with the sale of products, products, the design of the products, or warrant the economic value, energy savings, safety, durability or reliability of the products;</li>

<li>I understand that the Program Administrator has the right to inspect any installed products listed on this Completion Certificate;</li>

<li>I hereby transfer and assign any right to payment that I have arising from the PACE Funding Program to PACE Funding Group, LLC or its affiliates for the Financed Project;</li>

<li>Amount on this Completion Certificate if I indicated above in Section B.2 “Assign Payment” that payment should be paid to the Participating Contractor;</li>

<li>The products listed above are the products installed on my property;</li>

<li>I have obtained, or will obtain, all necessary final permits and/or inspections required in my jurisdiction;</li>

<li>It is my responsibility to pay the Participating Contractor for the full invoiced cost of the product(s) even if it is greater than the amount financed through the Program; and,</li>

<li>I hereby irrevocably consent to the recordation of the assessment contract and issuance of a bond.</li>
 </ul>
 <ul class="pdfLastSection">
            <li style="float:left;width:65%;">Property Owner 1 Signature: ______________</li>
            <li style="float:right;width:35%;"> Date: ______________</li>
        </ul>
        <p>Printed Name: <?php echo $owner1; ?></p>
        <?php if((isset($owner2) && $owner2!='')){ ?>
        <br />
        <ul class="pdfLastSection">
            <li style="float:left;width:65%;">Property Owner 2 Signature: ______________</li>
            <li style="float:right;width:35%;"> Date: ______________</li>
        </ul>
        <p>Printed Name: <?php echo $owner2; ?></p>
        <?php }?>    
    <style>
        .ntb
        {
            border-top:none !important;
        }
        .nrb
        {
            border-bottom:1px solid #fff !important;
            border-right:1px solid #fff !important;
        }
        .ft tr td:first-child,.st tr td:first-child,.tt tr td:first-child
        {
            border-left:none;
        }
        .ft tr.ftfr td,.st tr.stfr td,.tt tr.ttfr td
        {
            border-top:none;
        }
        .ft tr td,.st tr td,.tt tr td
        {
            border-left:1px solid #000;
            padding:5px;
            border-top:1px solid #000;
            vertical-align:top;
        }
        .st tr td
        {
            vertical-align:bottom;
        }
        .tt tr td
        {
            vertical-align:top;
        }
        .alphalist li
        {
            list-style-type:lower-latin;
        }

        .pdfLastSection
        {
            width:100%;
            padding:0;
            margin:0;
        }

        .pdfLastSection li
        {
            display:inline;
            width:40%;
            list-style-type:none;
        }
        .pdfHeaderTOP
        {
            width:100%;
            padding:0;
        }
        .pdfHeaderTOP li
        {
            list-style-type:none;
            display:inline-block;
        }
        .pdfMainHeading
        {
            text-align:center;
            color:#79B042;
            font-size:19px;
            width:60%;
            font-weight:bold;
        }

        h2{
            font-size: 16px;
            color:#79B042;
        }

        .pdfSection
        {
            width:90%;
            margin:auto;
            /*border: 1px solid red;*/
            font-size:13px;
            font-family: "arial";
        }



        .pdfListNumbered li
        {
            list-style-type:number;
            line-height:1.5;
        }

        .pdfListalpha li
        {
            list-style-type:lower-alpha;
            line-height:1.5;
        }

        .pdfListroman li
        {
            list-style-type:upper-roman;
            line-height:1.5;
        }
        .pdfListDisc li
        {
            list-style-type:disc;
        }
        .pdfOuter
        {
            border:1px solid #000;
            padding:0 10px;
            margin:20px 0;
        }

        p,td
        {
            font-size:12px;
        }

        p
        {
            margin-bottom:20px;
            line-height:1.5;
        }
        .borderDisplayMain td
    {
        border-left: 1px solid #000;
        border-top: 1px solid #000;
        height: 30px;
        padding:5px;
    }
    .borderDisplayMain td:first-child
    {
        border-left:0;
    }
    .notopBorder td
    {
        border-top:0;
    }
    </style>