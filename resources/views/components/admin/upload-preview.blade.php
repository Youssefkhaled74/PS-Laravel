@props([
  'name',
  'label' => null,
  'currentUrl' => null,
  'accept' => '*/*',
  'hint' => null,
  'variant' => 'square',
  'size' => 'md',
  'required' => false,
  'id' => null,
])

@php
  if (empty($name)) {
    throw new \InvalidArgumentException('The "name" attribute is required for upload-preview component.');
  }
  $uid = $id ?? 'upload_'.substr(md5((string)microtime(true).rand()),0,8);
  $accept = $accept ?? '*/*';
@endphp

<div class="upload-tile upload-{{ $variant }} upload-size-{{ $size }}" id="upload-{{ $uid }}" role="group" aria-labelledby="upload-label-{{ $uid }}">
  <div class="upload-meta">
    @if($label)
      <label id="upload-label-{{ $uid }}" class="upload-label">{{ $label }}</label>
    @endif
    @if($hint)
      <div class="upload-hint">{{ $hint }}</div>
    @endif
  </div>

  <div class="upload-inner">
    <div class="upload-preview-wrap">
      @if($currentUrl)
        <img id="preview-{{ $uid }}" src="{{ $currentUrl }}" class="upload-preview" alt="{{ $label ?? __('admin.preview') }}">
      @else
        <div id="preview-{{ $uid }}" class="upload-preview upload-placeholder" aria-hidden="true">
          <div class="placeholder-icon" aria-hidden="true">📄</div>
          <div class="placeholder-text">{{ __('admin.choose_file') }}</div>
        </div>
      @endif
    </div>

    <div class="upload-controls">
      <input type="file"
             name="{{ $name }}"
             id="input-{{ $uid }}"
             accept="{{ $accept }}"
             data-upload-preview
             data-preview-target="#preview-{{ $uid }}"
             data-filename-target="#filename-{{ $uid }}"
             data-initial-url="{{ $currentUrl ?? '' }}"
             aria-describedby="filename-{{ $uid }}"
             style="display:none"
             @if($required) required @endif
      >

      <div class="upload-actions">
        <button type="button" class="btn btn-ghost" data-file-trigger="#input-{{ $uid }}">{{ __('admin.choose_file') }}</button>
        <button type="button" class="btn btn-ghost" data-upload-clear data-target-input="#input-{{ $uid }}" aria-label="{{ __('admin.remove') }}">{{ __('admin.remove') }}</button>
      </div>

      <div class="upload-filename" aria-live="polite">
        <span id="filename-{{ $uid }}">@if($currentUrl){{ basename(parse_url($currentUrl, PHP_URL_PATH)) }}@endif</span>
      </div>
    </div>
  </div>
</div>
