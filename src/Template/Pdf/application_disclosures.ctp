<?php use Cake\I18n\Number; ?>
<style>
    @page {
        /* ensure you append the header/footer name with 'html_' */
        header: html_MyCustomHeader; /* sets <htmlpageheader name="MyCustomHeader"> as the header */   
        footer: html_MyCustomFooter; /* sets <htmlpagefooter name="MyCustomFooter"> as the footer */		
        margin-top: 150px;
        margin-bottom:80px
    }
</style>

<div class="pdfSection">

<htmlpageheader name="MyCustomHeader">
<img src="<?php echo SITE_FULL_URL; ?>/img/pacelogo-220.png" />
<ul style="width:1000px;border-bottom:1px solid #000;" class="pdfHeaderTOP">
	<li style="float:left;width:110px;font-size:10px;padding-bottom:5px;">T: 844-USE-PACE <br />www.pacefunding.com</li>
	<li style="float:right;width:500px;" class="pdfMainHeading">PACE Funding Application Disclosures</li>
	<div style="clear:both;"></div>
</ul>
</htmlpageheader>

<htmlpagefooter name="MyCustomFooter">
<div class="pdfFooterSection">
	<hr />
	<ul class="pdfLastSection">
		<li style="font-size:11px;float:left;width:48%;">Application ID: <?php echo $ApplicationID; ?></li>
		<li style="font-size:11px;float:right;text-align:right;width:51%;">PACE Funding Application Disclosures<br />v1.2 – <?php echo date('F, Y'); ?></li>
		<div style="clear:both;"></div>
	</ul>
</div>
</htmlpagefooter>

<p>The California Statewide Communities Development Authority (the "Authority") PACE Funding Program (the “Program”) finances installation of renewable energy, energy or water efficiency products, or electric vehicle charging infrastructure that are permanently fixed to a property owner’s real property (“Eligible Products”). Eligible Products will be financed upon the signing of an assessment contract between the Authority and the property owner (”Assessment Contract”). The Authority has retained PACE Funding, LLC (”PACE Funding”) to facilitate the Program, and you will see this name throughout the Program materials. The Authority and PACE Funding are referred to collectively therein as “Program Administrator.”</p>

<h2>Property Owner Acknowledgements</h2>	

<p>In order to participate in the Program, I understand that I need to meet the qualifications listed below. By signing this Application, I acknowledge and represent to the best of my knowledge that I and any other owner(s) of the property which is the subject of this application (the “Property”) meet these qualifications and I authorize the Program Administrator to obtain a credit report for each of the property owner(s) and/or trustees whose social security number is provided on this application.</p>

<ul class="pdfListNumbered">
	<li>Applicant(s) must be the owner(s) of record of the property;</li>
	<li>Mortgage-related debt on the property must not exceed 90.00% of the value of the property;</li>
	<li>Property owner(s) must be current on their property taxes and there must be no more than one late payment for the shorter of (i) the previous three (3) years, or (ii) since the present homeowner acquired the Property,</li>
	<li>Property owners must be current on all mortgage debt at the time of application and cannot have had more than one 30 day mortgage late payment over the previous 12 months;</li>
	<li>Property owner(s) must not have had any active bankruptcies within the last seven (7) years, and the Property must not be an asset in an active bankruptcy. However, for all jurisdictions with the exception of the City of San Diego, if a bankruptcy was discharged between two and seven years prior, and the property owner(s) have not had any additional late payments more than 60 days past due in the last 24 months, the property owner may be approved; and</li>
	<li>The property must not have any federal or state income tax liens, judgment liens, mechanic’s liens, or similar involuntary liens on the property. I understand that to qualify for the Program that the following requirements must be met:
	
		<ul class="pdfListalpha">
			<li>The amount to be financed under the Program must be less than 15% of the value of the Property on the first
			$700,000 value, and less than 10% of any remaining value of the property thereafter.</li>
			<li>The combined amount to be financed under the Program plus the mortgage related debt must not exceed 100% of the value of the Property.
				<ul class="pdfListroman">
					<li>For properties within the City of San Diego, all mortgage related debt plus the PACE Funding Financing amount must not exceed 95% of the value of the property.</li>
				</ul>
			
			</li>
			<li>All property owners must sign all required documentation, including but not limited to the Application, the Completion Certificate and the
				<ul class="pdfListroman">
					<li>Assessment Contract with all other required Financing Documents.</li>
				</ul>
			</li>
			<li>Following approval, my contractor or I must call the Program to identify the Eligible Products I would like to purchase, enter into an Assessment Contract with the Authority, and receive Notification to Proceed from the Program before beginning the installation of any Eligible Products. Products which have not been approved by the Program will not be funded.</li>
			<li>Interest rates may change from the approval date to receiving the Notification to Proceed.</li>
			
		</ul>
	
	
	
	</li>
</ul>

<p>By signing this Application, I hereby declare under penalty of perjury under the laws of the State of California all of the following:</p>

<ul class="pdfListNumbered">
	<li>That the information provided in this Application is true and correct as of the date set forth opposite my signature on the Application and that I understand that any intentional or negligent misrepresentation(s) of the information contained in this Application may result in civil liability and/or criminal penalties including, but not limited to, imprisonment, liability for monetary damages to the Authority, its agents, or successors and assigns, insurers and any other person who may suffer any loss due to reliance upon any misrepresentation which I have made in this Application, or both.</li>
	<li>I have the authority to authorize the Program Administrator to obtain a credit report for each of the property owner(s) and/or trustee(s) whose social security number(s) is provided on this Application.</li>
	<li>I understand that it is my responsibility to receive, read and understand all documents comprising the Program, which, in addition to information on the Program website, include the following:
		<ul class="pdfListalpha">
			<li>This Application;</li>
			<li>Privacy Policy Notice;</li>
			<li>Assessment   Contract; and</li>
			<li>Program Handbook.</li>
		</ul>
		<p>I have had an opportunity to ask Program representatives and/or my legal counsel any questions I have regarding the documents listed above. I understand I will be asked to sign the Assessment Contract, among other documents, as a pre-condition to the closing of the financing.</p>
	</li>
	<li>I am applying to participate in the Program. I have the authority, without the consent of any third party, to execute and deliver this Application, the Assessment Contract, and the various other documents and instruments referenced herein.</li>

	<li>I understand that the financing provided pursuant to the Assessment Contract will be repayable through an assessment levied against the Property. I understand that an assessment lien will be recorded by the Authority against the Property in the office of the County Recorder of the County of San Diego upon execution of the Assessment Contract. The property tax bill (which will include the assessment payments) for the Property will increase by the amount of these assessment installment payments. The Assessment Contract will specify the amount of the assessment, the assessment installments and the interest on the assessment to be collected on the property tax bill for the Property each year during the term specified in the Assessment Contract. The assessment and the interest and any penalties thereon will constitute a lien against the Property until they are paid. As with all tax and assessment liens, this lien will be senior to all existing and future private liens against the Property, including mortgages, deeds of trust and other security instruments.</li>

</ul>
<h2>Disclosures</h2>

<p>The following describes some (but not all) characteristics and risks of participation in the Program as well as laws to which the Program is subject. A full understanding of any item listed below can be gained only by reviewing the relevant laws, policy statements, and/or the contractual documents related to the Program. The Program Administrator is committed to your understanding each of the items listed below before you enter into an Assessment Contract, and invites you to ask Program representatives any questions regarding these items or if you need copies of any document related to the Program.</p>

<ul class="pdfListNumbered">
	<li>Program Disclosures and Disclaimers.
		<ul class="pdfListalpha">
			<li><b>Existing Mortgage.</b> The Program establishes the manner by which the Authority may finance, pursuant to Chapter 29 of Part 3 of Division 7 of the California Streets and Highways Code (commencing with Section 5898.10), the installation of Eligible Products. Eligible Products will be financed pursuant to an Assessment Contract between you and the Authority.
			
			<p>BEFORE COMPLETING A PROGRAM APPLICATION, YOU SHOULD CAREFULLY REVIEW ANY MORTGAGE AGREEMENT(S) OR OTHER SECURITY INSTRUMENT(S) WHICH AFFECT THE PROPERTY OR TO WHICH YOU AS THE PROPERTY OWNER ARE A PARTY. ENTERING INTO A PROGRAM ASSESSMENT CONTRACT WITHOUT THE CONSENT OF YOUR EXISTING LENDER(S) COULD CONSTITUTE AN EVENT OF DEFAULT UNDER SUCH AGREEMENTS OR SECURITY INSTRUMENTS. DEFAULTING UNDER AN EXISTING MORTGAGE AGREEMENT OR SECURITY INSTRUMENT COULD HAVE SERIOUS CONSEQUENCES TO YOU, WHICH COULD INCLUDE THE ACCELERATION OF THE REPAYMENT OBLIGATIONS DUE UNDER SUCH AGREEMENT OR SECURITY INSTRUMENT. IN ADDITION, FANNIE MAE AND FREDDIE MAC, THE OWNER OF A SIGNIFICANT PORTION OF ALL HOME MORTGAGES, STATED THAT THEY WOULD NOT PURCHASE HOME LOANS WITH ASSESSMENTS SUCH AS THOSE OFFERED BY the Authority. THIS MAY MEAN THAT PROPERTY OWNERS WHO SELL OR REFINANCE THEIR PROPERTY MAY BE REQUIRED TO PREPAY SUCH ASSESSMENTS AT THE TIME THEY CLOSE THEIR SALE OR REFINANCING.</p>

			<p>If your lender requires an impound for your property taxes, please consider notifying them of the annual assessment payment amount so they can adjust your impound amount.</p>
			
			</li>
			
			
			<li><b>Interest Rate.</b> You will be charged a fixed interest rate on your total financed amount. Your interest rate will be set at the time your financing documents are issued. Interest rates may change from the approval date to the date the Notification to Proceed is sent.</li>


			<li><b>Program Administration Fee.</b> At the time of closing, the Authority will charge you a one-time administration fee of <?php echo $formula_data['origination_fee']; ?>% of the principal amount of the assessment on the Property to cover the costs of administering the Program. This fee will be added to the assessment amount</li>

			<li><b>Recording Fee.</b> At the time of closing, the Authority will pass through the assessment recording fee of approximately <?php echo Number::currency($formula_data['lien_recording_fee'], 'USD'); ?> to you to cover the costs of recording the assessment. This fee will be added to the assessment amount.</li>

			<li><b>Assessment Administration Fee.</b> Each year, an annual assessment administrative fee will be added to the assessment lien amount on your property tax bill. Currently these costs are <?php echo Number::currency($AnnualAdminFee, 'USD'); ?> and there will be adjustments in subsequent years for cost of living increases, not to exceed $95.00.</li>

			<li><b>Interest Before First Payment:</b> Based on the date an assessment is recorded on your property the payment of assessment installments may not begin until the following year’s property tax bill. As a result interest will be added to the assessment amount for the period between your closing date and the date of your first assessment payment. The maximum amount of interest will be listed on your Final Payment Summary, which will be provided with your financing documents.</li>

			<li><b>Automated Valuation Model Disclosure.</b> You have the right to a copy of the automated valuation model (AVM) report used in connection with your application for credit. If you want to obtain a copy, please email or write to us at the address we have provided. We must hear from you no later than 90 days after we provide you with a notice of the action taken on your application or a notice of incompleteness, or in the case of a withdrawn application, 90 days after the withdrawal. An AVM is not an appraisal. It is a computerized property valuation system that is used to derive a real property value.</li>

			<li><b>Foreclosure.</b> Not later than October 1 each year, the Authority shall determine whether any annual assessment installment is not paid when due and shall have the right and obligation to order that any such delinquent payment, penalties, interest, and associated costs be collected by an action brought in Superior Court to foreclose the lien of such delinquent assessment installment in the manner provided and to the extent permitted by applicable law.</li>

			<li><b>Prepayment.</b> You have the option to pay off your assessment amount at any time in full, or in any amount of at least $2,500. A prepayment is calculated to include the principal amount of the assessment to be prepaid (Assessment Prepayment Amount) and interest on the Assessment Prepayment Amount to the earlier of March 2nd or September 2nd occurring at least 50 days following the date the prepayment is made.</li>

			<li><b>No Endorsement, Warranty or Liability.</b> The Authority and the Program do not endorse any manufacturer, contractor, product, or system, or in any way warranty such equipment, installation, or the efficiency or production capability of any equipment. The Authority and the Program make no representations and have no responsibility regarding the equipment and its installation, including the quality, safety, cost savings, efficiency or production capability of any equipment; or any compliance of the equipment or its installation with any applicable laws, regulations, codes, standards or requirements. Further, the Authority and the Program shall not be in any way liable for any incidental or consequential damages resulting from the equipment or its installation.</li>

			<li><b>Validation.</b> The Program may validate that installed Eligible Products meet Program eligibility requirements including requiring the applicant to provide additional sales receipts, contractor invoices, serial numbers or other identifying details, portions of packages or stickers originally attached to the installed Eligible Products beyond what the Program already requires to be provided. The Program reserves the right to perform independent on-site validation(s) of any Eligible Products financed by the Program even if permit inspections have already been completed. If a validation visit is required, Program staff will schedule any such on-site validation visit with the property owner, at any reasonable time and with reasonable notice. In addition, the Program reserves the right to perform online monitoring of any installed renewable energy systems’ generation data, if applicable, as well the tracking of energy consumption impacts and utility usage for any installed/financed product via property utility bill data. You, by submitting this application, consent to any such onsite validations, online monitoring, and utility bill energy usage analysis. By submitting this application, you also agree to sign the authorization form to participate in utility billing energy usage analysis to measure Program impact savings and participant satisfaction.</li>



		</ul>
	
	</li>
	<li>Legal Disclosures
		<ul class="pdfListalpha">
			<li><b>Equal Credit Opportunity Act (ECOA).</b> The Federal Equal Credit Opportunity Act prohibits creditors from discriminating against Credit Applicant(s) on the basis of race, color, religion, national origin, sex, marital status, age (provided that the applicant has the capacity to enter into a binding contract); because all or part of the applicant(s) income derives from any public assistance program; or because the applicant has in good faith exercised any right under the Consumer Credit Protection Act. The Federal Agency that administers compliance with this law concerning this creditor is the Federal Trade Commission, Division of Credit Practices, Washington,
			D.C. 20580.</li>

			<li><b>Fair Credit Reporting Act.</b> As part of assembling your Program application, the Authority has requested a consumer report bearing your credit worthiness, credit standing and credit capacity. This notice is given to you pursuant to the Fair Credit Reporting Act.</li>

			<li><b>The Housing Financial Discrimination Act Of 1977.</b> It is illegal to discriminate in the provision of or in the availability of financial assistance because of the consideration of:
				<ul class="pdfListroman">
					<li>trends, characteristics or conditions in the neighborhood or geographic area surrounding a housing accommodation, unless the financial institution can demonstrate in the particular case that such consideration is required to avoid an unsafe and unsound business practice; or</li>
					<li>race, color, religion, sex, marital status, domestic partnership, national origin or ancestry.</li>
				</ul>
			
			</li>
			
			<li><b>Patriot Act Disclosure.</b> To help the government fight the funding of terrorism and money laundering activities, Federal law requires all financial institutions to obtain, verify, and record information that identities each person who opens an account. What this means for you: As part of applying to the Program, the Authority may be required to ask for your name, address, date of birth, and other information that will allow it to identify you. The Authority may also need a copy of the driver's license or other identifying documents from any and all borrowers and guarantors.</li>

			<li><b>Communications with Legal Advisers.</b> If you have any questions about any agreements or security instruments which affect the Property or to which you are a party, or about your authority to execute the Program Application or enter into an Assessment Contract with the Authority without the prior consent of your existing lender(s), the Program strongly encourages you to consult with your own legal counsel and your lender(s). Program staff cannot provide you with advice about existing agreements or security instruments.</li>

			<li><b>Monitoring and Recording Telephone Calls.</b> The Program may monitor or record telephone calls for security and customer service purposes. By applying for PACE Funding Financing, you consent to have any phone conversations with the Program recorded or monitored.</li>

		</ul>
	
	</li>


</ul>
<pagebreak />
<h2>Property Owner Signature(s)</h2>
<div class="pdfOuter">
	<p>I declare that (i) I have received, read and understand the risks and characteristics of the Program described in the Property Owner Acknowledgments and Disclosures set forth in this Application and (ii) I have been informed that I must take the sole responsibility to satisfy myself that executing the Assessment Contract, receiving financing for Eligible Products, and consenting to the assessment levied against the Property will not constitute a default under any other agreement or security instrument (specifically the terms of any mortgage on the Property) which affects the Property or to which I am a party.</p>
	
	<ul class="pdfLastSection">
		<li style="float:left;width:48%;">Property Owner 1 Signature: <br /><br /><br /><br />__________________________________   </li>
		<li style="float:right;width:48%;"> Date:________________</li>
		<div style="clear:both;"></div>
	</ul>
	<p>Printed Name: <?php echo $owner1;?>
        <?php if($project['customer_detail']['property_ownership']=='Trust'){
            echo ' on behalf of '.$project['customer_detail']['trust_name'];
        }elseif($project['customer_detail']['property_ownership']=='Corporation or LLC'){
            echo ' on behalf of '.$project['customer_detail']['corporation_llc_name'];
        }?>
        </p>
	<?php if((isset($owner2) && $owner2!='')){ ?>
	<ul class="pdfLastSection">
		<li style="float:left;width:48%;">Property Owner 2 Signature: <br /><br /><br /><br />__________________________________   </li>
		<li style="float:right;width:48%;"> Date: ________________</li>
		<div style="clear:both;"></div>
	</ul>
	<p>Printed Name: <?php echo $owner2;?></p>
        <?php } ?>
</div>

</div>

<style>
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
	display:inline;
}
.pdfMainHeading
{
	color:#79B042;
	font-size:19px;
	font-weight:bold;
}

h2{
	font-size: 14px;
	color:#79B042;
}

.pdfSection
{
	width:90%;
	margin:auto;
	/*border: 1px solid red;*/
	font-size:12px;
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


p
{
	margin-bottom:20px;
	line-height:1.5;
}

div.header {

padding: 25px;



} 
</style>