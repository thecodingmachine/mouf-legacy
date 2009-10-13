<?php

/**
 * This class implements basic features for a data column, but does not provide the way to retrieve data from the DB row.
 *
 */
abstract class AbstractDataColumnInterface implements DataColumnInterface {
	
	private $title;
	private $sortColumn;
	private $width;
	private $formatter;
	private $useI18n;
	private $translationPrefix;
	private $translationSuffix;
	
	public function __construct($title=null, $sortColumn=null, $width=null, $formatter=null) {
		$this->title = $title;
		$this->sortColumn = $sortColumn;
		$this->width = $width;
		$this->formatter = $formatter;
	}
	
	/**
	 * Returns the title for the column
	 *
	 * @return string
	 */
	public function getTitle() {
		return $this->title;
	}
	
	/**
	 * The title for the column
	 *
	 * @Property
	 * @Compulsory
	 * @param string $title
	 */
	public function setTitle($title) {
		$this->title = $title;
	}
	
	/**
	 * Returns true if the column should be sortable, false otherwise.
	 *
	 * @return bool
	 */
	public function isSortable() {
		return !empty($this->sortColumn);
	}
	
	/**
	 * Returns the "sort column" to be passed to the datasource when the user wants to
	 * sort on that column.
	 *
	 * @return string
	 */
	public function getSortColumn() {
		return $this->sortColumn;
	}
	
	/**
	 * The "sort column" to be passed to the datasource when the user wants to sort on that column.
	 * If none is provided, the column is not sortable.
	 * 
	 * @Property
	 * @param string $sortColumn
	 */
	public function setSortColumn($sortColumn) {
		$this->sortColumn = $sortColumn;
	}
	
	/**
	 * Returns the width of the column (in pixels).
	 *
	 * @return int
	 */
	public function getWidth() {
		return $this->width;
	}
	
	/**
	 * The width of the column (in pixels).
	 *
	 * @Property
	 * @Compulsory
	 * @param int $width
	 */
	public function setWidth($width) {
		$this->width = $width;
	}
	
	/**
	 * Sets the formatter used to display that column.
	 * If no formatter is specified, content is displayed as text.
	 *
	 * @Property
	 * @param DataColumnFormatterInterface $formatter
	 */
	public function setFormatter(DataColumnFormatterInterface $formatter) {
		$this->formatter = $formatter;
	}
	
	/**
	 * Returns the formatter for this column.
	 *
	 * @return DataColumnFormatterInterface
	 */
	public function getFormatter() {
		return $this->formatter;
	}
	
	/**
	 * Sets if the data in the formatter is translated through the I18N mechanism (using FINE).
	 * If true, the content of the column will be used as a key.
	 * For instance, if the column contains: "common.yes", and if the French translation for "common.yes" is "Oui",
	 * then "Oui" will be displayed.
	 * 
	 * @Property
	 * @param bool $useI18n
	 */
	public function setUseI18n($useI18n) {
		$this->useI18n = $useI18n;
	}
	
	/**
	 * Returns true if the data in the formatter is translated through the I18N mechanism (using FINE).
	 *
	 * @return DataColumnFormatterInterface
	 */
	public function doesUseI18n() {
		return $this->useI18n;
	}

	/**
	 * The prefix for the I18N key.
	 * For instance, if the column contains: "yes", if the prefix is "common" and if the French translation for "common.yes" is "Oui",
	 * then "Oui" will be displayed.
	 *
	 * @Property
	 * @Compulsory
	 * @param string $prefix
	 */
	public function setTranslationPrefix($prefix) {
		$this->translationPrefix = $prefix;
	}
	
	/**
	 * Returns the prefix used in translation.
	 *
	 * @return string
	 */
	public function getTranslationPrefix() {
		return $this->translationPrefix;
	}
	
	/**
	 * The suffix for the I18N key.
	 * For instance, if the column contains: "yes", if the suffix is "common" and if the French translation for "yes.common" is "Oui",
	 * then "Oui" will be displayed.
	 *
	 * @Property
	 * @Compulsory
	 * @param string $suffix
	 */
	public function setTranslationSuffix($suffix) {
		$this->translationSuffix = $suffix;
	}
	
	/**
	 * Returns the suffix used in translation.
	 *
	 * @return string
	 */
	public function getTranslationSuffix() {
		return $this->translationSuffix;
	}
	
	/**
	 * Translates the value, if required.
	 *
	 * @param string $value
	 * @return string
	 */
	protected function getTranslatedValue($value) {
		if ($this->useI18n) {
			return iMsg($this->translationPrefix.$value.$this->translationSuffix);
		} else {
			return $value;
		}
	}
}
?>