<?php

class MF_GitHubConnector_Block_Adminhtml_Commits_Confirm_Form extends Mage_Adminhtml_Block_Widget_Form
{

    protected function _prepareForm()
    {
        $commit = Mage::registry('current_commit');

        $form = new Varien_Data_Form(); 
   
        $generalFieldset = $form->addFieldset('general', array('legend'=>Mage::helper('mf_gitHubConnector')->__('General')));
    
        $generalFieldset->addField('sha', 'note', array(
            'text'      => $commit->getSha(),
            'label'     => $this->__('Sha'),
        ));
        
        $generalFieldset->addField('message', 'note', array(
            'text'      => $commit->getMessage(),
            'label'     => $this->__('Message'),
        ));

        $generalFieldset->addField('committed_date', 'note', array(
            'text'      => $commit->getCommitterDate(),
            'label'     => $this->__('Committed Date'),
        ));
        
        $generalFieldset->addField('committer', 'note', array(
            'text'      => $commit->getCommitterName() . ' (' . $commit->getCommitterEmail() . ')',
            'label'     => $this->__('Commiter'),
        ));

        $filesFieldset = $form->addFieldset('files', array('legend'=>Mage::helper('mf_gitHubConnector')->__('Files')));
        
        $files = $commit->getFiles();
        
        foreach($files as $key => $file) {
            $afterElementHtml = '<br/><small><ul>';
            $afterElementHtml .= '<li>' . $file->getChanges() . ' ' . $this->__('changes') . ' : ' . $file->getAdditions() . ' ' . $this->__('additions') . ', ' . $file->getDeletions() . ' ' . $this->__('deletions') . '</li>';
            
            if (!$file->isConflicted()) {
                $afterElementHtml .= '<li style = "color:green">' . $this->__('No conflicts') . '</li>';
            } else {
                $conflicts = $file->getConflicts();
                $afterElementHtml .= '<li style = "color:red"><b>' . $this->__('Detected conflicts:') . '</b></li>';
                foreach($conflicts as $conflict) {
                    $afterElementHtml .= '<li style = "color:red">' . $conflict . '</li>';
                }
            }
            
            $afterElementHtml .= '</ul></small>';
        
            $filesFieldset->addField('file_' . $key, 'note', array(
                'text'      => $file->getFilename(),
                'label'     => $this->__($file->getStatus()),
                'after_element_html' => $afterElementHtml,
            ));
            
            if ($file->isConflicted()) {
                $filesFieldset->addField('file_' . $key . '_action', 'select', array(
                    'label'     => '',
                    'values'    => array(0 => $this->__('Skip this file'), 1 => $this->__('Replace this file')),
                ));
            }
        }
        
        foreach($files as $key => $file) {
            $filesFieldset = $form->addFieldset('file_details_' . $key, array('legend'=>$file->getFilename()));

            $filesFieldset->addField('file_details_' . $key . '_patch', 'note', array(
                'text'      => nl2br(htmlspecialchars($file->getPatch())),
                'label'     => '',
            ));
        }
        
        $this->setForm($form);

        return parent::_prepareForm();
    }

}
