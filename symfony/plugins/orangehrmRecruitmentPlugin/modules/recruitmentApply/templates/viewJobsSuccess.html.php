<?php use_javascript('../orangehrmRecruitmentPlugin/js/viewJobsSuccess'); ?>
<style type="text/css">
    
    div.mainHeading {
        margin: -20px 0px 0px 0px;
        width:auto;
        border:0;
        background-color: #FAD163;
        color: black;
        text-align:left;
    }

    #toggleJobList {
        float: right;
        margin: -10px 20px 0px 0px;
        font-size: 12px;
    }

    #toggleJobList span {
        text-decoration: underline;
        cursor: pointer; 
    }

    .vacancyDescription, .vacancyShortDescription {
        display: none;
        font-size: 12px;
        font-family: Arial,Verdana,Helvetica,sans-serif;
        line-height: 15px;
    }

    .vacancyShortDescription {
        display: block;
    }

    .applyLink {
        display: none;
    }

    .vacancyTitle :hover {
        cursor: pointer;
    }

    .plusOrMinusmark {
        text-align: right;
        margin-top: 10px;
        padding: 20px 25px 0px 0px;
        font-weight: lighter;
        font-size: 12px;
    }

    .plusMark, .minusMark {
        cursor: pointer;
    }

    .minusMark {
        display: none;
    }

    .verticalLine {
        border: medium none;
        height: 2px;
        margin: 20px 20px 10px;
    }
</style>
<div id="jobPage">
    <div class="box">
        <div class="maincontent">
            <div class="head">
                <h1><?php echo __('Active Job Vacancies'); ?></h1>
            </div>

            <div class="inner">
                <?php if (count($publishedVacancies) != 0): ?>                    
                    <div id="toggleJobList">
                        <span id="expandJobList"><?php echo __('Expand all') ?></span> | <span id="collapsJobList"><?php echo __('Collapse all'); ?></span>
                    </div>

                    <?php foreach ($publishedVacancies as $vacancy): ?>

                        <div class="plusOrMinusmark">
                            <span class="plusMark">[+]</span><span class="minusMark">[-]</span>
                        </div>

                        <div class="jobItem">

                            <div class="vacancyTitle">
                                <h3><?php echo $vacancy->getName(); ?></h3>
                            </div>

                            <pre class="vacancyShortDescription"><?php echo getShortDescription($vacancy->getDescription(), 250, "..."); ?></pre>
                            <pre class="vacancyDescription"><?php echo $vacancy->getDescription(); ?></pre>

                            <input type="button" class="apply" name="applyButton" value="<?php echo __("Apply"); ?>" onmouseout="moutButton(this);" onmouseover="moverButton(this);" />
                            <a href="<?php echo public_path('index.php/recruitmentApply/applyVacancy/id/' . $vacancy->getId(), true); ?>" class="applyLink"></a>

                        </div>
                        <hr class="verticalLine" />
                    <?php endforeach; ?>
                <?php else: ?>
                    <span class="noVacanciesMessage"><?php echo __('No active job vacancies to display'); ?></span>
                <?php endif; ?>

            </div>

        </div>

    </div>

</div>
<?php
/*
 * Get short description to show in default view in view job list
 * @param string $description full description
 * @param int $limit Number of characters show in short description
 * @param string $endString String added to end of the short description
 * @return string $description short description 
 */

function getShortDescription($description, $limit, $endString) {

    if (strlen($description) > $limit) {
        $subString = substr($description, 0, $limit);
        $wordArray = explode(" ", $subString);
        $description = substr($subString, 0, -(strlen($wordArray[count($wordArray) - 1]) + 1)) . $endString;
    }
    return $description;
}