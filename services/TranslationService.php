<?php
/**
 * Translation Service
 * 
 * This service handles translation of text using a translation API.
 * Currently using a mock implementation that simulates translation.
 * In a production environment, this would be replaced with a real API call
 * to a service like Google Translate, DeepL, or Azure Translator.
 */
class TranslationService {
    private $targetLanguage;
    
    /**
     * Constructor
     * 
     * @param string $targetLanguage Target language code (e.g., 'fr', 'es', 'de')
     */
    public function __construct($targetLanguage = 'fr') {
        $this->targetLanguage = $targetLanguage;
    }
    
    /**
     * Translate text to the target language
     * 
     * @param string $text Text to translate
     * @return string Translated text
     */
    public function translate($text) {
        if (empty($text)) {
            return '';
        }
        
        // In a real implementation, this would call an external API
        // For demonstration purposes, we'll use a simple mock translation
        
        // Log the translation request
        $this->logTranslation($text, $this->targetLanguage);
        
        // For demo purposes, we'll add a prefix to show it's "translated"
        // In a real implementation, this would be replaced with an API call
        return $this->mockTranslate($text, $this->targetLanguage);
    }
    
    /**
     * Set the target language
     * 
     * @param string $language Target language code
     */
    public function setTargetLanguage($language) {
        $this->targetLanguage = $language;
    }
    
    /**
     * Get the current target language
     * 
     * @return string Current target language code
     */
    public function getTargetLanguage() {
        return $this->targetLanguage;
    }
    
    /**
     * Mock translation function - in a real implementation, this would be replaced with an API call
     * 
     * @param string $text Text to translate
     * @param string $language Target language code
     * @return string Mocked translated text
     */
    private function mockTranslate($text, $language) {
        // This is a simple mock translation for demonstration purposes
        // In a real implementation, this would be replaced with an API call to Google Translate or similar service
        
        // Create more realistic translations with language-specific formatting
        switch ($language) {
            case 'fr':
                return $this->frenchTranslation($text);
            case 'es':
                return $this->spanishTranslation($text);
            case 'de':
                return $this->germanTranslation($text);
            case 'it':
                return $this->italianTranslation($text);
            case 'zh':
                return $this->chineseTranslation($text);
            case 'ar':
                return $this->arabicTranslation($text);
            default:
                return "[Translation to {$language}] " . $text;
        }
    }
    
    /**
     * French translation
     */
    private function frenchTranslation($text) {
        $translations = [
            'Hello' => 'Bonjour',
            'Welcome' => 'Bienvenue',
            'Forum' => 'Forum',
            'Thread' => 'Fil de discussion',
            'Comment' => 'Commentaire',
            'Post' => 'Publication',
            'User' => 'Utilisateur',
            'Admin' => 'Administrateur',
            'Investor' => 'Investisseur',
            'Entrepreneur' => 'Entrepreneur',
            'Content' => 'Contenu',
            'Discussion' => 'Discussion',
            'Reply' => 'Répondre',
            'View' => 'Voir',
            'Edit' => 'Modifier',
            'Delete' => 'Supprimer',
            'Create' => 'Créer',
            'Update' => 'Mettre à jour',
            'Submit' => 'Soumettre',
            'Cancel' => 'Annuler',
            'Save' => 'Enregistrer',
        ];
        
        $result = $text;
        foreach ($translations as $en => $fr) {
            $result = str_ireplace($en, $fr, $result);
        }
        
        return $result;
    }
    
    /**
     * Spanish translation
     */
    private function spanishTranslation($text) {
        $translations = [
            'Hello' => 'Hola',
            'Welcome' => 'Bienvenido',
            'Forum' => 'Foro',
            'Thread' => 'Hilo',
            'Comment' => 'Comentario',
            'Post' => 'Publicación',
            'User' => 'Usuario',
            'Admin' => 'Administrador',
            'Investor' => 'Inversor',
            'Entrepreneur' => 'Emprendedor',
            'Content' => 'Contenido',
            'Discussion' => 'Discusión',
            'Reply' => 'Responder',
            'View' => 'Ver',
            'Edit' => 'Editar',
            'Delete' => 'Eliminar',
            'Create' => 'Crear',
            'Update' => 'Actualizar',
            'Submit' => 'Enviar',
            'Cancel' => 'Cancelar',
            'Save' => 'Guardar',
        ];
        
        $result = $text;
        foreach ($translations as $en => $es) {
            $result = str_ireplace($en, $es, $result);
        }
        
        return $result;
    }
    
    /**
     * German translation
     */
    private function germanTranslation($text) {
        $translations = [
            'Hello' => 'Hallo',
            'Welcome' => 'Willkommen',
            'Forum' => 'Forum',
            'Thread' => 'Diskussion',
            'Comment' => 'Kommentar',
            'Post' => 'Beitrag',
            'User' => 'Benutzer',
            'Admin' => 'Administrator',
            'Investor' => 'Investor',
            'Entrepreneur' => 'Unternehmer',
            'Content' => 'Inhalt',
            'Discussion' => 'Diskussion',
            'Reply' => 'Antworten',
            'View' => 'Ansehen',
            'Edit' => 'Bearbeiten',
            'Delete' => 'Löschen',
            'Create' => 'Erstellen',
            'Update' => 'Aktualisieren',
            'Submit' => 'Absenden',
            'Cancel' => 'Abbrechen',
            'Save' => 'Speichern',
        ];
        
        $result = $text;
        foreach ($translations as $en => $de) {
            $result = str_ireplace($en, $de, $result);
        }
        
        return $result;
    }
    
    /**
     * Italian translation
     */
    private function italianTranslation($text) {
        $translations = [
            'Hello' => 'Ciao',
            'Welcome' => 'Benvenuto',
            'Forum' => 'Forum',
            'Thread' => 'Discussione',
            'Comment' => 'Commento',
            'Post' => 'Post',
            'User' => 'Utente',
            'Admin' => 'Amministratore',
            'Investor' => 'Investitore',
            'Entrepreneur' => 'Imprenditore',
            'Content' => 'Contenuto',
            'Discussion' => 'Discussione',
            'Reply' => 'Rispondere',
            'View' => 'Visualizzare',
            'Edit' => 'Modificare',
            'Delete' => 'Eliminare',
            'Create' => 'Creare',
            'Update' => 'Aggiornare',
            'Submit' => 'Inviare',
            'Cancel' => 'Annullare',
            'Save' => 'Salvare',
        ];
        
        $result = $text;
        foreach ($translations as $en => $it) {
            $result = str_ireplace($en, $it, $result);
        }
        
        return $result;
    }
    
    /**
     * Chinese translation
     */
    private function chineseTranslation($text) {
        $translations = [
            'Hello' => '你好',
            'Welcome' => '欢迎',
            'Forum' => '论坛',
            'Thread' => '主题',
            'Comment' => '评论',
            'Post' => '帖子',
            'User' => '用户',
            'Admin' => '管理员',
            'Investor' => '投资者',
            'Entrepreneur' => '企业家',
            'Content' => '内容',
            'Discussion' => '讨论',
            'Reply' => '回复',
            'View' => '查看',
            'Edit' => '编辑',
            'Delete' => '删除',
            'Create' => '创建',
            'Update' => '更新',
            'Submit' => '提交',
            'Cancel' => '取消',
            'Save' => '保存',
        ];
        
        $result = $text;
        foreach ($translations as $en => $zh) {
            $result = str_ireplace($en, $zh, $result);
        }
        
        return $result;
    }
    
    /**
     * Arabic translation
     */
    private function arabicTranslation($text) {
        $translations = [
            'Hello' => 'مرحبا',
            'Welcome' => 'أهلا بك',
            'Forum' => 'منتدى',
            'Thread' => 'موضوع',
            'Comment' => 'تعليق',
            'Post' => 'منشور',
            'User' => 'مستخدم',
            'Admin' => 'مشرف',
            'Investor' => 'مستثمر',
            'Entrepreneur' => 'رائد أعمال',
            'Content' => 'محتوى',
            'Discussion' => 'مناقشة',
            'Reply' => 'رد',
            'View' => 'عرض',
            'Edit' => 'تحرير',
            'Delete' => 'حذف',
            'Create' => 'إنشاء',
            'Update' => 'تحديث',
            'Submit' => 'إرسال',
            'Cancel' => 'إلغاء',
            'Save' => 'حفظ',
        ];
        
        $result = $text;
        foreach ($translations as $en => $ar) {
            $result = str_ireplace($en, $ar, $result);
        }
        
        return $result;
    }
    
    /**
     * Log translation request
     * 
     * @param string $text Text to translate
     * @param string $language Target language code
     */
    private function logTranslation($text, $language) {
        // In a real implementation, this might log to a file or database
        // For demonstration purposes, we'll just log to the PHP error log
        error_log(sprintf('Translation requested: "%s" to %s', substr($text, 0, 30) . (strlen($text) > 30 ? '...' : ''), $language));
    }
}
