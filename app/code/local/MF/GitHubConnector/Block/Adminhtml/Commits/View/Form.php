<?php

class MF_GitHubConnector_Block_Adminhtml_Commits_View_Form extends Mage_Adminhtml_Block_Widget_Form
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
        
        $publicationFieldset = $form->addFieldset('publication', array('legend'=>Mage::helper('mf_gitHubConnector')->__('Publication')));
        
        $publicationFieldset->addField('status', 'note', array(
            'text'      => $commit->getTextStatus(),
            'label'     => $this->__('Status'),
        ));
        
        if ($commit->isPublished()) {
            $publicationFieldset->addField('publication_date', 'note', array(
                'text'      => $commit->getPublisherDate(),
                'label'     => $this->__('Publication Date'),
            ));
        
            $publicationFieldset->addField('publisher', 'note', array(
                'text'      => $commit->getPublisherName(),
                'label'     => $this->__('Publisher'),
            ));
        }
        
        $filesFieldset = $form->addFieldset('files', array('legend'=>Mage::helper('mf_gitHubConnector')->__('Files')));
        
        $files = $commit->getFiles();
        
        foreach($files as $key => $file) {
            $filesFieldset->addField('file_' . $key, 'note', array(
                'text'      => $file->getFilename(),
                'label'     => $this->__($file->getStatus()),
            ));
        }
        
        $this->setForm($form);

        return parent::_prepareForm();
    }

}
